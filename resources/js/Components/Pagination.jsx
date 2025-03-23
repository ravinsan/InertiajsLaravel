import React, { useState, useEffect } from "react";
import { Link, router } from "@inertiajs/react";
import { ArrowLeft, ArrowRight } from "lucide-react";
import Select from "react-select";

export default function Pagination({ data }) { 
    console.log(data);

    const urlParams = new URLSearchParams(window.location.search);
    const initialPerPage = parseInt(urlParams.get("per_page")) || data.per_page;

    const [perPage, setPerPage] = useState(initialPerPage);

    const options = [
        { value: 10, label: "10" },
        { value: 25, label: "25" },
        { value: 50, label: "50" },
        { value: 100, label: "100" },
        { value: 200, label: "200" },
        { value: 500, label: "500" },
    ];

    const selectedOption = options.find((option) => option.value === perPage);

    const handlePerPageChange = (selectedOption) => {
        if (!selectedOption) return;
        const newPerPage = selectedOption.value;
        setPerPage(newPerPage);
        router.visit(`?page=1&per_page=${newPerPage}`, {
            preserveState: true,
            replace: true,
        });
    };

    return (
        <div className="px-4 py-3 flex mb-4.5 items-center justify-between sm:px-6">
            {data?.total > data.per_page && (
                <div className="hidden sm:flex-1 sm:flex items-center sm:items-center sm:justify-between">
                    <div className="flex items-center gap-4">
                        <div>
                            <p className="text-sm text-gray-700">
                                Showing{" "}
                                <span className="font-medium">{data.from}</span>{" "}
                                to{" "}
                                <span className="font-medium">{data.to}</span>{" "}
                                of{" "}
                                <span className="font-medium">
                                    {data.total}
                                </span>{" "}
                                results
                            </p>
                        </div>
                        <div className="flex items-center gap-2">
                            <p className="text-base text-gray-700">Per Page</p>
                            <Select
                                options={options}
                                value={selectedOption}
                                onChange={handlePerPageChange}
                                className="w-24"
                                menuPlacement="auto"
                            />
                        </div>
                    </div>
                    <div>
                        <nav
                            className="relative z-0 inline-flex rounded-md shadow-sm gap-1 -space-x-px"
                            aria-label="Pagination"
                        >
                            {data.links.map((pageLink, index) => (
                                <div
                                    className={`inline-flex items-center bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 ${
                                        pageLink.active
                                            ? "!bg-[#2563eb] !text-white"
                                            : ""
                                    }`}
                                    key={index}
                                >
                                    <Link
                                        href={pageLink.url}
                                        aria-current="page"
                                        className="p-1 h-7 w-7 flex items-center justify-center"
                                    >
                                        {pageLink.label ===
                                            "&laquo; Previous" && (
                                            <ArrowLeft size={18} />
                                        )}
                                        {pageLink.label === "Next &raquo;" && (
                                            <ArrowRight size={18} />
                                        )}
                                        {pageLink.label !==
                                            "&laquo; Previous" &&
                                            pageLink.label !==
                                                "Next &raquo;" && (
                                                <span>{pageLink.label}</span>
                                            )}
                                    </Link>
                                </div>
                            ))}
                        </nav>
                    </div>
                </div>
            )}
        </div>
    );
}