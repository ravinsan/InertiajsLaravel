import { Link } from "@inertiajs/react";
import React, { useState, useRef, useEffect } from "react";
import { RiMenu2Fill, RiArrowDownSLine, RiUserFill } from "react-icons/ri";

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
        <header className="bg-white shadow-md px-6 py-3 flex justify-between">
            {/* Sidebar Toggle Button */}
          <div className="flex items-center">
            <button
                onClick={toggleSidebar}
                className="p-2 rounded-full hover:bg-gray-200 flex items-center justify-center"
            >
                <RiMenu2Fill className="text-2xl text-gray-700" />
            </button>
            <h1 className="text-lg font-semibold">Dashboard</h1>
            </div>
            {/* Page Title */}

            {/* User Profile Dropdown */}
            <div className="relative" ref={dropdownRef}>
                <button
                    className="flex items-center gap-2 px-4 py-2 border rounded-lg hover:bg-gray-200"
                    onClick={toggleDropdown}
                >
                    <RiUserFill className="text-blue-500 text-3xl" />
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
                                <Link rel="stylesheet" href={route('logout')} method="post">Logout</Link>
                            </li>
                        </ul>
                    </div>
                )}
            </div>
        </header>
    );
};

export default Header;
