"use client"

import { useEffect, useState } from "react"
import Image from "next/image"

export default function LoaderPage() {
  const [progress, setProgress] = useState(0)
  const [isComplete, setIsComplete] = useState(false)

  useEffect(() => {
    const interval = setInterval(() => {
      setProgress((prev) => {
        if (prev >= 100) {
          setIsComplete(true)
          clearInterval(interval)
          return 100
        }
        return prev + Math.random() * 15
      })
    }, 200)

    return () => clearInterval(interval)
  }, [])

  return (
    <div className="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-4">
      <div className="text-center space-y-8 max-w-md w-full">
        {/* Logo Container */}
        <div className="relative">
          <div className={`transition-all duration-1000 ${isComplete ? "scale-110" : "scale-100"}`}>
            <div className="relative w-32 h-32 mx-auto mb-6">
              {/* Animated ring around logo */}
              <div className="absolute inset-0 rounded-full border-4 border-transparent border-t-red-800 animate-spin"></div>
              <div
                className="absolute inset-2 rounded-full border-2 border-transparent border-r-red-600 animate-spin animation-delay-150"
                style={{ animationDirection: "reverse" }}
              ></div>

              {/* Logo */}
              <div className="absolute inset-4 flex items-center justify-center bg-white rounded-full shadow-lg">
                <Image
                  src="/images/logoali3000.png"
                  alt="ali3000 consultores logo"
                  width={64}
                  height={64}
                  className="object-contain"
                  priority
                />
              </div>
            </div>
          </div>
        </div>

        {/* Company Name */}
        <div className="space-y-2">
          <h1 className="text-3xl font-bold text-gray-800 tracking-wide">ali3000</h1>
          <p className="text-lg text-red-800 font-medium uppercase tracking-widest">Consultores</p>
        </div>

        {/* Loading Progress */}
        <div className="space-y-4">
          <div className="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
            <div
              className="h-full bg-gradient-to-r from-red-800 to-red-600 rounded-full transition-all duration-300 ease-out"
              style={{ width: `${Math.min(progress, 100)}%` }}
            ></div>
          </div>

          <div className="flex justify-between items-center text-sm text-gray-600">
            <span>Cargando...</span>
            <span className="font-mono">{Math.round(Math.min(progress, 100))}%</span>
          </div>
        </div>

        {/* Loading Text Animation */}
        <div className="h-6">
          {!isComplete ? (
            <div className="flex items-center justify-center space-x-1">
              <div className="w-2 h-2 bg-red-800 rounded-full animate-bounce"></div>
              <div className="w-2 h-2 bg-red-700 rounded-full animate-bounce animation-delay-100"></div>
              <div className="w-2 h-2 bg-red-600 rounded-full animate-bounce animation-delay-200"></div>
            </div>
          ) : (
            <p className="text-green-600 font-medium animate-fade-in">¡Listo para comenzar!</p>
          )}
        </div>

        {/* Subtle background pattern */}
        <div className="absolute inset-0 opacity-5 pointer-events-none">
          <div className="absolute top-1/4 left-1/4 w-32 h-32 border border-red-800 rounded-full"></div>
          <div className="absolute bottom-1/4 right-1/4 w-24 h-24 border border-red-600 rounded-full"></div>
          <div className="absolute top-3/4 left-1/3 w-16 h-16 border border-red-700 rounded-full"></div>
        </div>
      </div>
    </div>
  )
}
