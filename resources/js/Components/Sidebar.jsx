import React, { useState, useRef, useEffect } from "react";
import {
    RiArrowDownSLine,
    RiDashboardFill,
    RiUserSettingsFill,
    RiUserAddFill,
    RiGroupFill,
} from "react-icons/ri";

const Sidebar = ({ isOpen }) => {
    const [isUserManagementOpen, setIsUserManagementOpen] = useState(false);

    const toggleUserManagement = () => {
        setIsUserManagementOpen(!isUserManagementOpen);
    };

    return (
        <>
            {/* Sidebar */}
            <div
                className={`bg-blue-700 text-white h-screen px-4 py-5 transition-all duration-300 ${
                    isOpen ? "w-64" : "w-20"
                }`}
            >
                <h2 className={`text-xl font-bold mb-6 ${!isOpen && "hidden"}`}>
                    Dashboard
                </h2>

                {/* Sidebar Items */}
                <ul className="space-y-2">
                    <li className="flex items-center gap-3 px-4 py-2 hover:bg-gray-700 rounded cursor-pointer">
                        <RiDashboardFill className="text-xl" />
                        {isOpen && <span>Dashboard</span>}
                    </li>

                    {/* User Management Dropdown */}
                    <li>
                        <button
                            className="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-700 rounded"
                            onClick={toggleUserManagement}
                        >
                            <div className="flex items-center gap-3">
                                <RiGroupFill className="text-xl" />
                                {isOpen && <span>User Management</span>}
                            </div>
                            {isOpen && (
                                <RiArrowDownSLine
                                    className={`${
                                        isUserManagementOpen ? "rotate-180" : ""
                                    }`}
                                />
                            )}
                        </button>
                        {isUserManagementOpen && isOpen && (
                            <ul className="ml-8 mt-2 space-y-2">
                                <li className="flex items-center gap-3 px-4 py-2 hover:bg-gray-700 rounded cursor-pointer">
                                    <RiUserSettingsFill className="text-xl" />
                                    {isOpen && <span>User List</span>}
                                </li>
                                <li className="flex items-center gap-3 px-4 py-2 hover:bg-gray-700 rounded cursor-pointer">
                                    <RiUserAddFill className="text-xl" />
                                    {isOpen && <span>Add User</span>}
                                </li>
                            </ul>
                        )}
                    </li>
                </ul>
            </div>
        </>
    );
};

export default Sidebar;
