@extends('layout')

@section('title', 'Register')

@section('content')
    <section style="max-width: 960px; margin: 0 auto; padding: 1rem; display: flex; align-items: center; justify-content: center; min-height: calc(100vh - 120px);">
        <div style="width: 100%; max-width: 420px;">
            <div style="background: #2d3748; border: 1px solid #4a5568; border-radius: 0.75rem; padding: 2rem; color: #87ceeb;">
                <header style="margin-bottom: 1.5rem; text-align: center;">
                    <h1 style="margin: 0; color: #4a90e2; font-size: 1.75rem;">Create Account</h1>
                    <p style="margin: 0.5rem 0 0; color: #a0aec0; font-size: 0.9rem;">Join LevelUpp and start your journey</p>
                </header>

                <form method="POST" action="/register" style="display: grid; gap: 1rem;">
                    @csrf

                    <div style="display: grid; gap: 0.5rem;">
                        <label style="color: #cbd5e1; font-size: 0.9rem; font-weight: 500;">Full Name</label>
                        <input type="text" name="name" placeholder="John Doe" value="{{ old('name') }}" style="background: #1a202c; border: 1px solid #4a5568; color: #87ceeb; padding: 0.75rem; border-radius: 0.375rem; font-size: 0.95rem;" required>
                        @error('name')
                            <span style="color: #f56565; font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: grid; gap: 0.5rem;">
                        <label style="color: #cbd5e1; font-size: 0.9rem; font-weight: 500;">Email Address</label>
                        <input type="email" name="email" placeholder="mail@example.com" value="{{ old('email') }}" style="background: #1a202c; border: 1px solid #4a5568; color: #87ceeb; padding: 0.75rem; border-radius: 0.375rem; font-size: 0.95rem;" required>
                        @error('email')
                            <span style="color: #f56565; font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: grid; gap: 0.5rem;">
                        <label style="color: #cbd5e1; font-size: 0.9rem; font-weight: 500;">Password</label>
                        <input type="password" name="password" placeholder="••••••••" style="background: #1a202c; border: 1px solid #4a5568; color: #87ceeb; padding: 0.75rem; border-radius: 0.375rem; font-size: 0.95rem;" required>
                        @error('password')
                            <span style="color: #f56565; font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: grid; gap: 0.5rem;">
                        <label style="color: #cbd5e1; font-size: 0.9rem; font-weight: 500;">Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••" style="background: #1a202c; border: 1px solid #4a5568; color: #87ceeb; padding: 0.75rem; border-radius: 0.375rem; font-size: 0.95rem;" required>
                        @error('password_confirmation')
                            <span style="color: #f56565; font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" style="background: #4a90e2; color: white; border: none; padding: 0.75rem; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.95rem; margin-top: 0.5rem;">Create Account</button>
                </form>

                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #4a5568; text-align: center;">
                    <p style="margin: 0; color: #a0aec0; font-size: 0.9rem;">Already have an account? <a href="/login" style="color: #4a90e2; text-decoration: none; font-weight: 600;">Sign in</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection