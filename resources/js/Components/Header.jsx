import React, { useState, useRef, useEffect } from "react";
import { RiMenu2Fill, RiArrowDownSLine } from "react-icons/ri";

const Header = ({ toggleSidebar }) => {
    const [isDropdownOpen, setIsDropdownOpen] = useState(false);
    const dropdownRef = useRef(null);

    // ✅ Toggle Dropdown Function
    const toggleDropdown = () => {
        setIsDropdownOpen(!isDropdownOpen);
    };

    // ✅ Close dropdown when clicking outside
    useEffect(() => {
        function handleClickOutside(event) {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
                setIsDropdownOpen(false);
            }
        }
        document.addEventListener("mousedown", handleClickOutside);
        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, []);

    return (
        <header className="bg-white shadow-md px-6 py-3 flex items-center justify-between">
            {/* Sidebar Toggle Button */}
            <button
                onClick={toggleSidebar}
                className="p-2 rounded-full hover:bg-gray-200 flex items-center justify-center"
            >
                <RiMenu2Fill className="text-2xl text-gray-700" />
            </button>

            {/* Page Title */}
            <h1 className="text-lg font-semibold">Dashboard</h1>

            {/* User Profile Dropdown */}
            <div className="relative" ref={dropdownRef}>
                <button
                    className="flex items-center gap-2 px-4 py-2 border rounded-lg hover:bg-gray-200"
                    onClick={toggleDropdown}
                >
                    <img
                        src="https://via.placeholder.com/30"
                        alt="User"
                        className="w-8 h-8 rounded-full"
                    />
                    <span>Admin</span>
                    <RiArrowDownSLine className="text-lg" />
                </button>

                {/* Dropdown Menu */}
                {isDropdownOpen && (
                    <div className="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg">
                        <ul className="py-2">
                            <li className="px-4 py-2 hover:bg-gray-100 cursor-pointer">Profile</li>
                            <li className="px-4 py-2 hover:bg-gray-100 cursor-pointer">Settings</li>
                            <li className="px-4 py-2 hover:bg-gray-100 cursor-pointer text-red-500">
                                Logout
                            </li>
                        </ul>
                    </div>
                )}
            </div>
        </header>
    );
};

export default Header;
