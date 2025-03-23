import Pagination from "@/Components/Pagination";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm } from "@inertiajs/react";
import React, { useState } from "react";
import { FaEdit, FaPlus, FaTrash } from "react-icons/fa";
import { toast } from "react-toastify";
import Swal from "sweetalert2";

const Index = ({ categories, auth }) => {
    
    const urlParams = new URLSearchParams(window.location.search);
    const { delete: destroy, get } = useForm();
    const [filterText, setFilterText] = useState("");
    const [statusFilter, setStatusFilter] = useState(urlParams.get("status") || "");
    const [megaMegaMenuStatusFilter, setMegaMenuStatusFilter] = useState(urlParams.get("mega_menu_status") || "");
    const [frontendMenuStatusFilter, setFrontendMenuStatusFilter] = useState(urlParams.get("frontend_menu_status") || "");
    const [pageDesignStatusFilter, setPageDesignStatusFilter] = useState(urlParams.get("page_design_status") || "");
    
    const [sortColumn, setSortColumn] = useState(urlParams.get("sort_column") || "id");
    const [sortOrder, setSortOrder] = useState(urlParams.get("sort_order") || "desc");

    // Filter Status
    const handleStatusFilterChange = (e) => {
        const selectedStatus = e.target.value;
        setStatusFilter(selectedStatus);
        
        get(route("categories.index", { status: selectedStatus }), {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Filter Mega Menu Status
    const handleMegaMenuStatusFilterChange = (e) => {
        const selectedStatus = e.target.value;
        setMegaMenuStatusFilter(selectedStatus);
        
        get(route("categories.index", { mega_menu_status: selectedStatus }), {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Filter Frontend Menu Status
    const handleFrontendStatusFilterChange = (e) => {
        const selectedStatus = e.target.value;
        setFrontendMenuStatusFilter(selectedStatus);
        
        get(route("categories.index", { frontend_menu_status: selectedStatus }), {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Filter Page design status
    const handlePageDesignStatusFilterChange = (e) => {
        const selectedStatus = e.target.value;
        setPageDesignStatusFilter(selectedStatus);
        
        get(route("categories.index", { page_design_status: selectedStatus }), {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Searching text
    const handleFilterTextChange = (e) => {
        setFilterText(e.target.value);
        get(route("categories.index", { search: e.target.value }), {
            preserveState: true,
            preserveScroll: true,
        });
    }    

    // Sorting
    const handleSort = (column) => {
        const newSortOrder = sortColumn === column && sortOrder === "asc" ? "desc" : "asc";
        setSortColumn(column);
        setSortOrder(newSortOrder);
        get(route("categories.index", { sort_column: column, sort_order: newSortOrder }), {
            preserveState: true, 
            preserveScroll: true, 
        });
    };
    

    // Record delete
    const handleDelete = (id) => {
        Swal.fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Yes, delete it!",
        }).then((result) => {
          if (result.isConfirmed) {
            destroy(route("categories.destroy", id), {
              onSuccess: () => {
                toast.success("Category has been successfully deleted");
                Swal.fire("Deleted!", "Category has been successfully deleted.", "success");
              },
              onError: () => toast.error("Failed to delete category. Try again."),
            });
          }
        });
      };
    
    // Status Change
    const handleStatusChange = (id) =>{
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to change this status!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, change it!",
          }).then((result) => {
            if (result.isConfirmed) {
              get(route("categories.status", id), {
                onSuccess: () => {
                  toast.success("Category status has been successfully changed");
                  Swal.fire("Status!", "Category status has been successfully changed.", "success");
                },
                onError: () => toast.error("Failed to change category status. Try again."),
              });
            }
          });
    }

    // Page Design Status
    const handlePageDesignStatus = (id) => {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to change this status!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, change it!",
          }).then((result) => {
            if (result.isConfirmed) {
              get(route("categories.page_design_status", id), {
                onSuccess: () => {
                  toast.success("Page design status has been successfully changed");
                  Swal.fire("Page design status!", "Page design status has been successfully changed.", "success");
                },
                onError: () => toast.error("Failed to change page design status. Try again."),
              });
            }
          });
    }

    // Frontend Menu Status
    const handleFrontendMenuStatus = (id) =>{
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to change this status!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, change it!",
          }).then((result) => {
            if (result.isConfirmed) {
              get(route("categories.frontend_menu_status", id), {
                onSuccess: () => {
                  toast.success("Frontend menu status has been successfully changed");
                  Swal.fire("Frontend menu status!", "Frontend menu status has been successfully changed.", "success");
                },
                onError: () => toast.error("Failed to change frontend menu status. Try again."),
              });
            }
          });
    }

    // Mega Menu Status
    const handleMegaMenuStatus = (id) =>{
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to change this status!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, change it!",
          }).then((result) => {
            if (result.isConfirmed) {
              get(route("categories.mega_menu_status", id), {
                onSuccess: () => {
                  toast.success("Mega menu status has been successfully changed");
                  Swal.fire("Mega menu status!", "Mega menu status has been successfully changed.", "success");
                },
                onError: () => toast.error("Failed to change mega menu status. Try again."),
              });
            }
          });
    }
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="text-2xl font-bold text-gray-800 dark:text-gray-200">
                    Category
                </h2>
            }
        >
            <Head title="Category" />
            <div className="max-w-5xl mx-auto bg-white shadow-lg rounded-lg p-6 mt-6 ">
                <div className="flex justify-between items-center ">
                    <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">
                        Category
                    </h3>
                    <Link
                        href="/categories/create"
                        className="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                    >
                        <FaPlus className="mr-2" /> Create Category
                    </Link>
                </div>

                <div className="flex flex-wrap gap-2 mb-4">
                        <input
                            type="text"
                            placeholder="Search..."
                            className="p-2 border border-gray-300 rounded-lg w-full md:w-96 focus:ring-2 focus:ring-blue-500"
                            value={filterText}
                            onChange={handleFilterTextChange}
                        />
                        <select
                            className="p-2 border border-gray-300 rounded-lg w-full md:w-48 focus:ring-2 focus:ring-blue-500"
                            value={statusFilter}
                            onChange={handleStatusFilterChange} 
                        >
                            <option value="all">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <select
                            className="p-2 border border-gray-300 rounded-lg w-full md:w-48 focus:ring-2 focus:ring-blue-500"
                            value={megaMegaMenuStatusFilter}
                            onChange={handleMegaMenuStatusFilterChange} 
                        >
                            <option value="all">All Mega Status</option>
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                        <select
                            className="p-2 border border-gray-300 rounded-lg w-full md:w-48 focus:ring-2 focus:ring-blue-500"
                            value={frontendMenuStatusFilter}
                            onChange={handleFrontendStatusFilterChange} 
                        >
                            <option value="all">All Frontend Status</option>
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                        <select
                            className="p-2 border border-gray-300 rounded-lg w-full md:w-48 focus:ring-2 focus:ring-blue-500"
                            value={pageDesignStatusFilter}
                            onChange={handlePageDesignStatusFilterChange} 
                        >
                            <option value="all">All Page Design Status</option>
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div>


                <div className="overflow-x-auto">
                    <table className="w-full border border-gray-300 rounded-lg shadow-md">
                        <thead>
                            <tr className="bg-blue-600 text-white">
                                <th
                                    className="p-2 cursor-pointer"
                                    onClick={() => handleSort("id")}
                                >
                                    ID{" "}
                                    {sortColumn === "id" &&
                                        (sortOrder === "asc" ? "▲" : "▼")}
                                </th>
                                <th className="p-2 cursor-pointer" onClick={() => handleSort("name")}>
                                    Category Name {sortColumn === "name" && (sortOrder === "asc" ? "▲" : "▼")}
                                </th>
                                <th
                                    className="p-2 cursor-pointer"
                                    onClick={() => handleSort("parent_id")}
                                >
                                    Parent Category{" "}
                                    {sortColumn === "parent_id" &&
                                        (sortOrder === "asc" ? "▲" : "▼")}
                                </th>
                                <th
                                    className="p-2 cursor-pointer"
                                    onClick={() => handleSort("slug")}
                                >
                                    Slug{" "}
                                    {sortColumn === "slug" &&
                                        (sortOrder === "asc" ? "▲" : "▼")}
                                </th>
                                <th
                                    className="p-2 cursor-pointer"
                                    onClick={() => handleSort("order_id")}
                                >
                                    Order Number{" "}
                                    {sortColumn === "order_id" &&
                                        (sortOrder === "asc" ? "▲" : "▼")}
                                </th>
                                <th
                                    className="p-2 cursor-pointer"
                                    onClick={() => handleSort("image")}
                                >
                                    Image{" "}
                                    {sortColumn === "image" &&
                                        (sortOrder === "asc" ? "▲" : "▼")}
                                </th>
                                <th
                                    className="p-2 cursor-pointer"
                                    onClick={() =>
                                        handleSort("mega_menu_status")
                                    }
                                >
                                    Mega Menu Status{" "}
                                    {sortColumn === "mega_menu_status" &&
                                        (sortOrder === "asc" ? "▲" : "▼")}
                                </th>
                                <th
                                    className="p-2 cursor-pointer"
                                    onClick={() =>
                                        handleSort("frontend_menu_status")
                                    }
                                >
                                    Frontend Menu Status{" "}
                                    {sortColumn === "frontend_menu_status" &&
                                        (sortOrder === "asc" ? "▲" : "▼")}
                                </th>
                                <th
                                    className="p-2 cursor-pointer"
                                    onClick={() =>
                                        handleSort("page_design_status")
                                    }
                                >
                                    Page Design Status{" "}
                                    {sortColumn === "page_design_status" &&
                                        (sortOrder === "asc" ? "▲" : "▼")}
                                </th>
                                <th
                                    className="p-2 cursor-pointer"
                                    onClick={() => handleSort("status")}
                                >
                                    Status{" "}
                                    {sortColumn === "status" &&
                                        (sortOrder === "asc" ? "▲" : "▼")}
                                </th>
                                <th
                                    className="p-2 cursor-pointer"
                                    onClick={() => handleSort("action")}
                                >
                                    Action{" "}
                                    {sortColumn === "action" &&
                                        (sortOrder === "asc" ? "▲" : "▼")}
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            {categories.data.map((category, index) => (
                                <tr
                                    key={category.id}
                                    className="border hover:bg-gray-100"
                                >
                                    <td className="p-1 border whitespace-nowrap">
                                        {sortOrder === "desc"
                                            ? (categories.per_page * (categories.current_page - 1)) + index + 1
                                            : categories.total - ((categories.current_page - 1) * categories.per_page) - index}
                                    </td>
                                    <td className="p-1 border whitespace-nowrap">
                                        {category.name}
                                    </td>
                                    <td className="p-1 border whitespace-nowrap">
                                        {category.parent
                                            ? category.parent.name
                                            : "N/A"}
                                    </td>
                                    <td className="p-1 border whitespace-nowrap">
                                        {category.slug}
                                    </td>
                                    <td className="p-1 border whitespace-nowrap">
                                        {category.order_id}
                                    </td>
                                    <td className="p-1 border whitespace-nowrap">
                                        <img
                                            src={`image/${category.image}`}
                                            alt={category.name}
                                            className="w-12 h-12 object-cover rounded"
                                        />
                                    </td>

                                    <td className="p-1 border whitespace-nowrap">
                                        <button
                                            className={`px-2 py-1 text-xs font-semibold rounded ${
                                                category.mega_menu_status
                                                    ? "bg-blue-500 text-white"
                                                    : "bg-gray-300 text-gray-700"
                                            }`}
                                            onClick={() => handleMegaMenuStatus(category.id)}
                                        >
                                            {category.mega_menu_status
                                                ? "Enabled"
                                                : "Disabled"}
                                        </button>
                                    </td>

                                    <td className="p-1 border whitespace-nowrap">
                                        <button
                                            className={`px-2 py-1 text-xs font-semibold rounded ${
                                                category.frontend_menu_status
                                                    ? "bg-green-500 text-white"
                                                    : "bg-gray-300 text-gray-700"
                                            }`}
                                            onClick={() => handleFrontendMenuStatus(category.id)}
                                        >
                                            {category.frontend_menu_status
                                                ? "Enabled"
                                                : "Disabled"}
                                        </button>
                                    </td>

                                    <td className="p-1 border whitespace-nowrap">
                                        <button
                                            className={`px-2 py-1 text-xs font-semibold rounded ${
                                                category.page_design_status
                                                    ? "bg-green-500 text-white"
                                                    : "bg-gray-300 text-gray-700"
                                            }`}
                                            onClick={() => handlePageDesignStatus(category.id)}
                                        >
                                            {category.page_design_status
                                                ? "Enabled"
                                                : "Disabled"}
                                        </button>
                                    </td>

                                    <td className="p-1 border whitespace-nowrap">
                                        <button
                                            className={`px-2 py-1 text-xs font-semibold rounded ${
                                                category.status
                                                    ? "bg-green-600 text-white"
                                                    : "bg-red-600 text-white"
                                            }`}
                                            onClick={() => handleStatusChange(category.id)}
                                        >
                                            {category.status
                                                ? "Active"
                                                : "Inactive"}
                                        </button>
                                    </td>

                                    <td className="p-1 mt-2 flex gap-2 justify-center whitespace-nowrap">
                                        <Link href={route('categories.edit', category.id)}
                                            className="p-1 bg-blue-100 text-blue-600 rounded-full hover:bg-blue-500 hover:text-white transition duration-300 shadow-sm"
                                            title="Edit"
                                        >
                                            <FaEdit size={14} />
                                        </Link>

                                        <button 
                                            className="p-1 bg-red-100 text-red-600 rounded-full hover:bg-red-500 hover:text-white transition duration-300 shadow-sm"
                                            onClick={() => handleDelete(category.id)}
                                            title="Delete">
                                            <FaTrash size={14} />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Pagination data={categories} />
            </div>
        </AuthenticatedLayout>
    );
};

export default Index;
