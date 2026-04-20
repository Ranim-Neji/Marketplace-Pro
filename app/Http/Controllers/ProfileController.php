<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Laravel\Facades\Image;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'active']);
    }

    public function edit()
    {
        return view('pages.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone'            => ['nullable', 'string', 'max:20'],
            'address'          => ['nullable', 'string', 'max:500'],
            'bio'              => ['nullable', 'string', 'max:500'],
            'shop_name'        => ['nullable', 'string', 'max:100'],
            'shop_description' => ['nullable', 'string', 'max:1000'],
            'avatar'           => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password'     => ['nullable', 'min:8', 'confirmed'],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $filename = 'avatars/' . uniqid() . '.jpg';
            $image = Image::read($request->file('avatar'));
            $image->cover(200, 200); // square crop
            Storage::disk('public')->put($filename, $image->toJpeg(85));
            $validated['avatar'] = $filename;
        }

        // Handle password change
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $validated['password'] = Hash::make($request->new_password);
        }

        // Handle email change → require re-verification
        if ($validated['email'] !== $user->email) {
            $user->email_verified_at = null;
            $user->fill($validated)->save();
            $user->sendEmailVerificationNotification();
            return redirect()->route('verification.notice')
                ->with('info', 'Please verify your new email address.');
        }

        unset($validated['current_password'], $validated['new_password']);
        $user->fill($validated)->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Account deleted successfully.');
    }

    public function becomeVendor(Request $request)
    {
        $request->validate([
            'shop_name'        => 'required|string|max:100|unique:users',
            'shop_description' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->update([
            'is_vendor'        => true,
            'shop_name'        => $request->shop_name,
            'shop_description' => $request->shop_description,
        ]);
        $user->assignRole('vendor');

        return redirect()->route('vendor.products.index')
            ->with('success', 'Welcome! Your vendor account is now active.');
    }
}
