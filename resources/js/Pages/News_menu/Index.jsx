import React from 'react'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

const Index = ({ auth, records}) => {
  
  return (
    <>
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
     
                     <div className="flex justify-between items-center mb-4">
                         <div className="flex space-x-2">
                             <input
                                 type="text"
                                 placeholder="Search..."
                                 className="p-2 border border-gray-300 rounded-lg w-96 focus:ring-2 focus:ring-blue-500"
                                 value={filterText}
                                 onChange={(e) => setFilterText(e.target.value)}
                             />
                             <select
                                 className="p-2 border border-gray-300 rounded-lg w-48 focus:ring-2 focus:ring-blue-500"
                                 value={statusFilter}
                                 onChange={(e) => setStatusFilter(e.target.value)}
                             >
                                 <option value="">All</option>
                                 <option value="1">Active</option>
                                 <option value="0">Inactive</option>
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
                                     <th
                                         className="p-2 cursor-pointer"
                                         onClick={() => handleSort("name")}
                                     >
                                         Category Name{" "}
                                         {sortColumn === "name" &&
                                             (sortOrder === "asc" ? "▲" : "▼")}
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
                                 {paginatedCategories.map((category) => (
                                     <tr
                                         key={category.id}
                                         className="border hover:bg-gray-100"
                                     >
                                         <td className="p-1 border whitespace-nowrap">
                                             {category.id}
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
     
                     <div className="flex justify-between items-center mt-6">
                         <button
                             className="px-4 py-2 bg-gray-400 text-white rounded-lg disabled:opacity-50"
                             disabled={currentPage === 1}
                             onClick={() => setCurrentPage(currentPage - 1)}
                         >
                             Prev
                         </button>
                         <span className="text-lg font-medium">
                             Page {currentPage}
                         </span>
                         <button
                             className="px-4 py-2 bg-blue-600 text-white rounded-lg disabled:opacity-50"
                             disabled={
                                 currentPage * itemsPerPage >=
                                 filteredCategories.length
                             }
                             onClick={() => setCurrentPage(currentPage + 1)}
                         >
                             Next
                         </button>
                     </div>
                 </div>
             </AuthenticatedLayout>
    </>
  )
}

export default Index