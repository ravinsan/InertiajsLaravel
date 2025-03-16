import { useState } from "react";
import Header from "@/Components/Header";
import Sidebar from "@/Components/Sidebar";

export default function Authenticated({ user, header, children }) {
    const [isSidebarOpen, setIsSidebarOpen] = useState(true);

    const toggleSidebar = () => {
        setIsSidebarOpen((prev) => !prev);
    };

    return (
        <div className="flex">
            {/* Sidebar */}
            <Sidebar isOpen={isSidebarOpen} />

            {/* Main Content */}
            <div className="flex-1 bg-gray-100 min-h-screen">
                {/* Header */}
                <Header toggleSidebar={toggleSidebar} isSidebarOpen={isSidebarOpen} />

                {/* Main Content */}
                <main>{children}</main>
            </div>
        </div>
    );
}
