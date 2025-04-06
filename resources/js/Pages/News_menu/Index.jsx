import React, { useState } from 'react'
import { Head, Link, useForm } from "@inertiajs/react";
import { FaEdit, FaPlus, FaTrash } from "react-icons/fa";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Pagination from '@/Components/Pagination';
import Swal from 'sweetalert2';
import { toast } from 'react-toastify';

const Index = ({ auth, records}) => {
  
    const {delete: destroy, get} = useForm();
    const urlParams = new URLSearchParams(window.location.search);
    const [filterText, setFilterText] = useState("");
    const [statusFilter, setStatusFilter] = useState(urlParams.get("status") || "");
    const [megaMegaMenuStatusFilter, setMegaMenuStatusFilter] = useState(urlParams.get("mega_menu_status") || "");
    const [sortColumn, setSortColumn] = useState(urlParams.get("sort_column") || "id");
    const [sortOrder, setSortOrder] = useState(urlParams.get("sort_order") || "desc");

    // Searching text
    const handleFilterTextChange = (e) => {
        const searchText = e.target.value;
        setFilterText(searchText);
        get(route("news-menus.index", { search: searchText }), {
            preserveState: true,
            preserveScroll: true,
        })
    }
    // Filter Status
    const handleStatusFilterChange = (e) => {
        const selectedStatus = e.target.value;
        setStatusFilter(selectedStatus);
        
        get(route("news-menus.index", { status: selectedStatus }), {
            preserveState: true,
            preserveScroll: true,
        });
    }
    // Filter Mega Menu Status
    const handleMegaMenuStatusFilterChange = (e) => {
        const selectedStatus = e.target.value;
        setMegaMenuStatusFilter(selectedStatus);
        
        get(route("news-menus.index", { mega_menu_status: selectedStatus }), {
            preserveState: true,
            preserveScroll: true,
        });
    }
    // Sorting
    const handleSort = (column) => {
        const newSortOrder = sortColumn === column && sortOrder === "asc" ? "desc" : "asc";
        setSortColumn(column);
        setSortOrder(newSortOrder);
        get(route("news-menus.index", { sort_column: column, sort_order: newSortOrder }), {
            preserveState: true, 
            preserveScroll: true, 
        });
    };
    // Delete news menu
    const handleDelete = (id) =>{
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
            destroy(route("news-menus.destroy", id), {
              onSuccess: () => {
                toast.success("News menu has been successfully deleted");
                Swal.fire("Deleted!", "News menu has been successfully deleted.", "success");
              },
              onError: () => toast.error("Failed to delete news menu. Try again."),
            });
          }
        });
    }
    // Status Change
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
                   get(route("news-menus.mega.menu.status", id), {
                     onSuccess: () => {
                       toast.success("News mega menu status has been successfully changed");
                       Swal.fire("News mega menu status!", "News mega menu status has been successfully changed.", "success");
                     },
                     onError: () => toast.error("Failed to change mews mega menu status. Try again."),
                   });
                 }
               });
         } 

    // Mega menu Status Change
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
              get(route("news-menus.status", id), {
                onSuccess: () => {
                  toast.success("News menu status has been successfully changed");
                  Swal.fire("Status!", "News menu status has been successfully changed.", "success");
                },
                onError: () => toast.error("Failed to change mews menu status. Try again."),
              });
            }
          });
    } 
  return (
    <>
     <AuthenticatedLayout
                 user={auth.user}
                 header={
                     <h2 className="text-2xl font-bold text-gray-800 dark:text-gray-200">
                         News Menu
                     </h2>
                 }
             >
                 <Head title="News Menu" />
                 <div className="max-w-5xl mx-auto bg-white shadow-lg rounded-lg p-6 mt-6 ">
                     <div className="flex justify-between items-center ">
                         <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">
                             News Menu
                         </h3>
                         <Link
                             href="/news-menus/create"
                             className="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                         >
                             <FaPlus className="mr-2" /> Create News Menu
                         </Link>
                     </div>
     
                     <div className="flex justify-between items-center mb-4">
                         <div className="flex space-x-2">
                             <input
                                 type="text"
                                 placeholder="Search..."
                                 className="p-2 border border-gray-300 rounded-lg w-96 focus:ring-2 focus:ring-blue-500"
                                 value={filterText}
                                 onChange={handleFilterTextChange}
                             />
                             <select
                                 className="p-2 border border-gray-300 rounded-lg w-48 focus:ring-2 focus:ring-blue-500"
                                 value={statusFilter}
                                 onChange={handleStatusFilterChange} 
                             >
                                 <option value="all">All Status</option>
                                 <option value="1">Active</option>
                                 <option value="0">Inactive</option>
                             </select>
                             <select
                                 className="p-2 border border-gray-300 rounded-lg w-48 focus:ring-2 focus:ring-blue-500"
                                 value={megaMegaMenuStatusFilter}
                                 onChange={handleMegaMenuStatusFilterChange} 
                             >
                                 <option value="all">All Mega Menu Status</option>
                                 <option value="1">Verticle</option>
                                 <option value="0">Horizontal</option>
                             </select>
                         </div>
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
                                         Name {sortColumn === "name" && (sortOrder === "asc" ? "▲" : "▼")}
                                     </th>
                                     <th
                                         className="p-2 cursor-pointer"
                                         onClick={() => handleSort("parent_id")}
                                     >
                                         Parent Menu{" "}
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
                                 {records.data.map((value, index) => (
                                     <tr
                                         key={value.id}
                                         className="border hover:bg-gray-100"
                                     >
                                         <td className="p-1 border whitespace-nowrap">
                                             {sortOrder === "desc"
                                                 ? (records.per_page * (records.current_page - 1)) + index + 1
                                                 : records.total - ((records.current_page - 1) * records.per_page) - index}
                                         </td>
                                         <td className="p-1 border whitespace-nowrap">
                                             {value.name}
                                         </td>
                                         <td className="p-1 border whitespace-nowrap">
                                             {value.parent
                                                 ? value.parent.name
                                                 : "N/A"}
                                         </td>
                                         <td className="p-1 border whitespace-nowrap">
                                             {value.slug}
                                         </td>
                                         <td className="p-1 border whitespace-nowrap">
                                             <button
                                                 className={`px-2 py-1 text-xs font-semibold rounded ${
                                                     value.mega_menu_status
                                                         ? "bg-blue-500 text-white"
                                                         : "bg-gray-300 text-gray-700"
                                                 }`}
                                                 onClick={() => handleMegaMenuStatus(value.id)}
                                             >
                                                 {value.mega_menu_status
                                                     ? "Verticle"
                                                     : "Horizontal"}
                                             </button>
                                         </td>
     
                                         <td className="p-1 border whitespace-nowrap">
                                             <button
                                                 className={`px-2 py-1 text-xs font-semibold rounded ${
                                                     value.status
                                                         ? "bg-green-600 text-white"
                                                         : "bg-red-600 text-white"
                                                 }`}
                                                 onClick={() => handleStatusChange(value.id)}
                                             >
                                                 {value.status
                                                     ? "Active"
                                                     : "Inactive"}
                                             </button>
                                         </td>
     
                                         <td className="p-1 mt-2 flex gap-2 justify-center whitespace-nowrap">
                                             <Link href={route('news-menus.edit', value.id)}
                                                 className="p-1 bg-blue-100 text-blue-600 rounded-full hover:bg-blue-500 hover:text-white transition duration-300 shadow-sm"
                                                 title="Edit"
                                             >
                                                 <FaEdit size={14} />
                                             </Link>
     
                                             <button 
                                                 className="p-1 bg-red-100 text-red-600 rounded-full hover:bg-red-500 hover:text-white transition duration-300 shadow-sm"
                                                 onClick={() => handleDelete(value.id)}
                                                 title="Delete">
                                                 <FaTrash size={14} />
                                             </button>
                                         </td>
                                     </tr>
                                 ))}
                             </tbody>
                         </table>
                     </div>
     
                     <Pagination data={records} />
                 </div>
             </AuthenticatedLayout>
    </>
  )
}

export default Index