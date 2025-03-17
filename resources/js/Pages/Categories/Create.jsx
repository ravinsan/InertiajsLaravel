import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm } from '@inertiajs/react';
import React, { useEffect, useState } from 'react';

const Create = ({ auth, categories }) => {
  const { data, setData, post, processing, errors } = useForm({
    name: '',
    slug: '',
    parent_id: '',
    image: null,
    order_id: '',
    status: '1',
  });

  const [isSlugEdited, setIsSlugEdited] = useState(false);

  // Function to generate slug
  const generateSlug = (text) => {
    return text
      .toLowerCase()
      .trim()
      .replace(/\s+/g, '-')  // Replace spaces with -
      .replace(/[^a-z0-9-]/g, '') // Remove special characters
      .replace(/-+/g, '-');  // Remove duplicate -
  };

  // Auto-update slug if it hasn't been manually edited
  useEffect(() => {
    if (!isSlugEdited) {
      setData('slug', generateSlug(data.name));
    }
  }, [data.name]);

  const handleSlugChange = (e) => {
    setData('slug', e.target.value);
    setIsSlugEdited(true); // Mark as manually edited
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    post('/categories'); // Adjust route if needed
  };

  return (
    <AuthenticatedLayout 
      user={auth.user} 
      header={<h2 className="text-2xl font-bold text-gray-800 dark:text-gray-200">Create Category</h2>}
    >
      <Head title="Create Category" />
      
      <div className="max-w-5xl mx-auto bg-white shadow-lg rounded-lg p-6 mt-6">
        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Add New Category</h3>

        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Input Fields - 2 per row */}
          <div className="grid grid-cols-2 gap-4">
            {/* Category Name */}
            <div>
              <label className="block text-sm font-medium text-gray-700">Category Name</label>
              <input 
                type="text"
                value={data.name}
                onChange={(e) => setData('name', e.target.value)}
                className="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                placeholder="Category Name"
                required
              />
              {errors.name && <p className="text-red-600 text-sm mt-1">{errors.name}</p>}
            </div>

            {/* Slug (Auto-filled, but editable) */}
            <div>
              <label className="block text-sm font-medium text-gray-700">Slug</label>
              <input 
                type="text"
                value={data.slug}
                onChange={handleSlugChange}
                className="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                placeholder="Slug"
              />
              {errors.slug && <p className="text-red-600 text-sm mt-1">{errors.slug}</p>}
            </div>

            {/* Parent Category Dropdown */}
            <div>
              <label className="block text-sm font-medium text-gray-700">Parent Category</label>
              <select 
                value={data.parent_id}
                onChange={(e) => setData('parent_id', e.target.value)}
                className="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
              >
                <option value="">Select Parent</option>
                {categories.map((category) => (
                  <option key={category.id} value={category.id}>{category.name}</option>
                ))}
              </select>
            </div>

            {/* Image Upload */}
            <div>
              <label className="block text-sm font-medium text-gray-700">Image</label>
              <input 
                type="file"
                onChange={(e) => setData('image', e.target.files[0])}
                className="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
              />
            </div>

            {/* Order ID */}
            <div>
              <label className="block text-sm font-medium text-gray-700">Order Id</label>
              <input 
                type="number"
                value={data.order_id}
                onChange={(e) => setData('order_id', e.target.value)}
                className="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                placeholder="Order Id"
              />
            </div>
          </div>

          {/* Submit Button - Centered */}
          <div className="flex justify-center">
            <button 
              type="submit"
              disabled={processing}
              className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
            >
              {processing ? 'Saving...' : 'Save Category'}
            </button>
          </div>
        </form>
      </div>
    </AuthenticatedLayout>
  );
};

export default Create;
