import React from 'react'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

const Index = ({auth}) => {
  return (
    <>
      <AuthenticatedLayout
                  user={auth.user}
                  header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Post List</h2>}
              >
                  <Head title="Dashboard" />
      
                  Posts List
              </AuthenticatedLayout>
    </>
  )
}

export default Index