import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { RiUserFill, RiUserStarFill, RiUserUnfollowFill } from "react-icons/ri";

export default function Dashboard({ auth }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />
            <div className="p-6">
                              {/* Stats Cards */}
                              <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-6">
                                  <div className="bg-white p-4 rounded-lg shadow-md flex items-center">
                                      <RiUserFill className="text-blue-500 text-3xl" />
                                      <div className="ml-4">
                                          <p className="text-gray-600">Total Users</p>
                                          <p className="text-xl font-bold">1,245</p>
                                      </div>
                                  </div>
                                  <div className="bg-white p-4 rounded-lg shadow-md flex items-center">
                                      <RiUserStarFill className="text-green-500 text-3xl" />
                                      <div className="ml-4">
                                          <p className="text-gray-600">Active Users</p>
                                          <p className="text-xl font-bold">950</p>
                                      </div>
                                  </div>
                                  <div className="bg-white p-4 rounded-lg shadow-md flex items-center">
                                      <RiUserUnfollowFill className="text-red-500 text-3xl" />
                                      <div className="ml-4">
                                          <p className="text-gray-600">
                                              Pending Requests
                                          </p>
                                          <p className="text-xl font-bold">120</p>
                                      </div>
                                  </div>
                              </div>

                              {/* Recent Users Table */}
                              <div className="bg-white p-6 rounded-lg shadow-md">
                                  <h3 className="text-lg font-semibold mb-4">
                                      Recent Users
                                  </h3>
                                  <table className="w-full border-collapse">
                                      <thead>
                                          <tr className="bg-gray-200 text-left">
                                              <th className="p-3">Name</th>
                                              <th className="p-3">Email</th>
                                              <th className="p-3">Status</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <tr className="border-t">
                                              <td className="p-3">John Doe</td>
                                              <td className="p-3">john@example.com</td>
                                              <td className="p-3">
                                                  <span className="bg-green-100 text-green-700 px-2 py-1 rounded">
                                                      Active
                                                  </span>
                                              </td>
                                          </tr>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
        </AuthenticatedLayout>
    );
}
