import React from 'react';
import { useState } from 'react';

function IndexPage() {
  return (
    <div className="h-screen w-screen bg-gradient-to-r from-green-500 to-green-400 flex flex-col items-center justify-center text-white">
      <h1 className="text-6xl font-bold">JCW Finance</h1>
      <p className="italic mt-4">We're here to help you with the finances of life</p>
      <div className="absolute top-5 right-5 flex gap-4">
        <button className="border border-white py-2 px-4 rounded-full">Create Account</button>
        <button className="border border-white py-2 px-4 rounded-full">Login</button>
      </div>
    </div>
  );
}

function ProfilePage() {
  const [showPassword, setShowPassword] = useState(false);

  return (
    <div className="h-screen w-screen bg-white flex flex-col items-center justify-center">
      <h1 className="text-4xl font-bold">Hello, "User"!</h1>
      <p className="italic mt-2">Account Details</p>

      <div className="mt-8 space-y-4">
        <div>
          <label className="block italic">Username:</label>
          <input className="w-80 h-10 bg-gradient-to-r from-green-300 to-green-500 rounded-md" />
        </div>
        <div>
          <label className="block italic">Password:</label>
          <div className="relative">
            <input className="w-80 h-10 bg-gradient-to-r from-green-300 to-green-500 rounded-md" type={showPassword ? 'text' : 'password'} />
            <button className="absolute right-2 top-2" onClick={() => setShowPassword(!showPassword)}>👁️</button>
          </div>
        </div>
        <div>
          <label className="block italic">Email:</label>
          <input className="w-80 h-10 bg-gradient-to-r from-green-300 to-green-500 rounded-md" />
        </div>
      </div>

      <button className="mt-8 italic text-green-500">Advanced Settings</button>
    </div>
  );
}

function BudgetPage() {
  return (
    <div className="h-screen w-screen bg-white p-10">
      <h1 className="text-3xl font-bold mb-6">My Budget</h1>

      <div className="grid grid-cols-2 gap-10">
        <div className="bg-gray-300 w-full h-64 flex items-center justify-center">Money saved over time</div>

        <div className="space-y-6">
          <div>
            <label>Add To Savings</label>
            <input className="w-64 h-10 bg-green-100 rounded-md ml-4" />
            <button className="ml-4 bg-gray-300 px-4 py-2 rounded-md">Add</button>
          </div>
          <div>
            <label>Remove From Savings</label>
            <input className="w-64 h-10 bg-green-100 rounded-md ml-4" />
            <button className="ml-4 bg-gray-300 px-4 py-2 rounded-md">Remove</button>
          </div>
          <div>
            <label>Change Savings Goal</label>
            <input className="w-64 h-10 bg-green-100 rounded-md ml-4" />
            <button className="ml-4 bg-gray-300 px-4 py-2 rounded-md">Edit</button>
          </div>
        </div>
      </div>

      <div className="mt-10">
        <div className="bg-gray-300 w-full h-16">Your Savings Goal</div>
        <div className="bg-gray-300 w-48 h-10 mt-4">You'll reach this on...</div>
      </div>
    </div>
  );
}

export default function App() {
  return <IndexPage />;
}

// You can replace <IndexPage /> with <ProfilePage /> or <BudgetPage /> to view those pages.
