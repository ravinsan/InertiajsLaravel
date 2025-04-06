import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm } from '@inertiajs/react';
import React, { useEffect, useState } from 'react';
import { toast } from "react-toastify";
import { IoReturnUpBackSharp } from "react-icons/io5"; 
import { Link } from '@inertiajs/react';

const Edit = ({auth, regions, Region}) => {
  const { data, setData, post, processing, errors } = useForm({
        _method: 'PUT',
      name: Region.name,
      slug: Region.slug,
      parent_id: Region.parent_id,
    })

    const [isSlugEdited, setIsSlugEdited] = useState(false);
    
      const generateSlug = (text) => {
        return text
          .toLowerCase()
          .trim()
          .replace(/\s+/g, '-')
          .replace(/[^a-z0-9-]/g, '')
          .replace(/-+/g, '-');
      };
    
      useEffect(() => {
        if (!isSlugEdited && data.name) {
          setData((prev) => ({ ...prev, slug: generateSlug(data.name) }));
        }
      }, [data.name]);
    
      const handleSlugChange = (e) => {
        const newSlug = e.target.value;
        if (newSlug !== data.slug) {
          setData('slug', newSlug);
          setIsSlugEdited(true);
        }
      };
      const handleSubmit = (e) => {
          e.preventDefault();
      
          post(route('regions.update', Region.id ), {
            onSuccess: () => {
              setData({
                name: '',
                slug: '',
                parent_id: '',
              });
              toast.success('Region has been updated successfully');
            },
            onError: (errors) => {
              Object.values(errors).forEach((error) => toast.error(error[0] || 'An error occurred'));
            }
          });
        }
  return (
    <AuthenticatedLayout 
          user={auth.user} 
          header={<h2 className="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit Region</h2>}
        >
          <Head title="Edit Region" />
    
          <div className="max-w-5xl mx-auto bg-white shadow-lg rounded-lg p-6 mt-6">
            {/* Back Button & Heading */}
              <Link 
                href={route('regions.index')} 
                className="flex items-center text-blue-600 hover:text-blue-800"
              >
                <IoReturnUpBackSharp className="mr-2" size={40} fontWeight={"bold"} /> Back
              </Link>
            <div className="flex items-center mb-4">
              <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-300 ml-4">Edit Region</h3>
            </div>
    
            <form onSubmit={handleSubmit} className="space-y-6">
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700">Region Name</label>
                  <input 
                    type="text"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    className="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Region Name"
                  />
                  {errors.name && <p className="text-red-600 text-sm mt-1">{errors.name}</p>}
                </div>
    
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
    
                <div>
                  <label className="block text-sm font-medium text-gray-700">Parent Region</label>
                  <select 
                    value={data.parent_id}
                    onChange={(e) => setData('parent_id', e.target.value)}
                    className="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  >
                    <option value="">Select Parent</option>
                    {regions.map((region) => (
                      <option key={region.id} value={region.id}>{region.name}</option>
                    ))}
                  </select>
                </div>
    
              </div>
    
              <div className="flex justify-center">
                <button 
                  type="submit"
                  disabled={processing}
                  className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                >
                  {processing ? 'Saving...' : 'Save Region'}
                </button>
              </div>
            </form>
          </div>
        </AuthenticatedLayout>
  )
}

export default Edit