import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import React, { useState } from "react";
import { FaPlus, FaSearch } from "react-icons/fa";

const Index = ({ categories, auth }) => {
  const [filterText, setFilterText] = useState("");
  const [sortColumn, setSortColumn] = useState("id");
  const [sortOrder, setSortOrder] = useState("asc");
  const [currentPage, setCurrentPage] = useState(1);
  const [statusFilter, setStatusFilter] = useState("");
  const itemsPerPage = 5;

  const handleSort = (column) => {
    if (sortColumn === column) {
      setSortOrder(sortOrder === "asc" ? "desc" : "asc");
    } else {
      setSortColumn(column);
      setSortOrder("asc");
    }
  };

  const filteredCategories = [...categories]
    .filter((category) =>
      category.name.toLowerCase().includes(filterText.toLowerCase())
    )
    .filter((category) =>
      statusFilter ? category.status.toString() === statusFilter : true
    )
    .sort((a, b) => {
      if (sortOrder === "asc") {
        return a[sortColumn] > b[sortColumn] ? 1 : -1;
      } else {
        return a[sortColumn] < b[sortColumn] ? 1 : -1;
      }
    });

  const paginatedCategories = filteredCategories.slice(
    (currentPage - 1) * itemsPerPage,
    currentPage * itemsPerPage
  );

  return (
    <AuthenticatedLayout user={auth.user} header={<h2 className="text-2xl font-bold text-gray-800 dark:text-gray-200">Category</h2>}>
      <Head title="Category" />
      <div className="max-w-5xl mx-auto bg-white shadow-lg rounded-lg p-6 mt-6 ">
        <div className="flex justify-between items-center ">
        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Category</h3>
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
                <th className="p-2 cursor-pointer" onClick={() => handleSort("id")}>
                  ID {sortColumn === "id" && (sortOrder === "asc" ? "▲" : "▼")}
                </th>
                <th className="p-2 cursor-pointer" onClick={() => handleSort("name")}>
                  Name {sortColumn === "name" && (sortOrder === "asc" ? "▲" : "▼")}
                </th>
                <th className="p-2 cursor-pointer" onClick={() => handleSort("status")}>
                  Status {sortColumn === "status" && (sortOrder === "asc" ? "▲" : "▼")}
                </th>
              </tr>
            </thead>
            <tbody>
              {paginatedCategories.map((category) => (
                <tr key={category.id} className="border hover:bg-gray-100">
                  <td className="p-2 border">{category.id}</td>
                  <td className="p-2 border">{category.name}</td>
                  <td className={`p-2 border font-semibold ${category.status ? "text-green-600" : "text-red-600"}`}>
                    {category.status ? "Active" : "Inactive"}
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
          <span className="text-lg font-medium">Page {currentPage}</span>
          <button
            className="px-4 py-2 bg-blue-600 text-white rounded-lg disabled:opacity-50"
            disabled={currentPage * itemsPerPage >= filteredCategories.length}
            onClick={() => setCurrentPage(currentPage + 1)}
          >
            Next
          </button>
        </div>
      </div>

    </AuthenticatedLayout>
  );
};

export default Index;
