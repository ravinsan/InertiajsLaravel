import React from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm } from '@inertiajs/react';
import { IoReturnUpBackSharp } from "react-icons/io5";

const SettingsForm = ({ auth }) => {
    const { data, setData, post, processing, errors } = useForm({
        mail_driver: '',
        smtp_host: '',
        smtp_port: '',
        smtp_user: '',
        smtp_password: '',
        email_encryption: '',
        contact_email: '',
        mail_from_name: '',
        mail_from_address: '',
        facebook: '',
        twitter: '',
        instagram: '',
        linkedin: '',
        youtube: '',
        youtube_url: '',
        image_url: '',
        logo: null,
        mini_logo: null,
        domy_image: null,
        fev_icon: null,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('settings.update'), {
            onSuccess: () => {
                toast.success('Settings updated successfully');
            },
            onError: (errors) => {
                Object.values(errors).forEach((error) => toast.error(error[0] || 'An error occurred'));
            },
        });
    };

    return (
        <AuthenticatedLayout user={auth.user} header={<h2 className="text-2xl font-bold text-gray-800 dark:text-gray-200">Settings</h2>}>
            <Head title="Settings" />
            
            <div className="max-w-5xl mx-auto bg-white shadow-lg rounded-lg p-6 mt-6">
                <form onSubmit={handleSubmit} className="space-y-6">
                    <h3 className="text-lg font-semibold text-gray-700">Email Setting</h3>
                    <div className="grid grid-cols-2 gap-4">
                        <input type="text" placeholder="Mail Driver" value={data.mail_driver} onChange={(e) => setData('mail_driver', e.target.value)} className="input" />
                        <input type="text" placeholder="SMTP Host" value={data.smtp_host} onChange={(e) => setData('smtp_host', e.target.value)} className="input" />
                        <input type="text" placeholder="SMTP Port" value={data.smtp_port} onChange={(e) => setData('smtp_port', e.target.value)} className="input" />
                        <input type="text" placeholder="Email Encryption" value={data.email_encryption} onChange={(e) => setData('email_encryption', e.target.value)} className="input" />
                        <input type="text" placeholder="SMTP User" value={data.smtp_user} onChange={(e) => setData('smtp_user', e.target.value)} className="input" />
                        <input type="password" placeholder="SMTP Password" value={data.smtp_password} onChange={(e) => setData('smtp_password', e.target.value)} className="input" />
                        <input type="text" placeholder="Contact Email" value={data.contact_email} onChange={(e) => setData('contact_email', e.target.value)} className="input" />
                        <input type="text" placeholder="Mail From Name" value={data.mail_from_name} onChange={(e) => setData('mail_from_name', e.target.value)} className="input" />
                        <input type="text" placeholder="Mail From Address" value={data.mail_from_address} onChange={(e) => setData('mail_from_address', e.target.value)} className="input" />
                    </div>

                    <h3 className="text-lg font-semibold text-gray-700">Social Site Setting</h3>
                    <div className="grid grid-cols-2 gap-4">
                        <input type="text" placeholder="Facebook" value={data.facebook} onChange={(e) => setData('facebook', e.target.value)} className="input" />
                        <input type="text" placeholder="Twitter" value={data.twitter} onChange={(e) => setData('twitter', e.target.value)} className="input" />
                        <input type="text" placeholder="Instagram" value={data.instagram} onChange={(e) => setData('instagram', e.target.value)} className="input" />
                        <input type="text" placeholder="LinkedIn" value={data.linkedin} onChange={(e) => setData('linkedin', e.target.value)} className="input" />
                        <input type="text" placeholder="YouTube" value={data.youtube} onChange={(e) => setData('youtube', e.target.value)} className="input" />
                        <input type="text" placeholder="YouTube URL" value={data.youtube_url} onChange={(e) => setData('youtube_url', e.target.value)} className="input" />
                    </div>

                    <h3 className="text-lg font-semibold text-gray-700">Logo Setting</h3>
                    <div className="grid grid-cols-2 gap-4">
                        <input type="file" onChange={(e) => setData('logo', e.target.files[0])} className="input" />
                        <input type="file" onChange={(e) => setData('mini_logo', e.target.files[0])} className="input" />
                        <input type="file" onChange={(e) => setData('domy_image', e.target.files[0])} className="input" />
                        <input type="file" onChange={(e) => setData('fev_icon', e.target.files[0])} className="input" />
                    </div>

                    <div className="flex justify-center">
                        <button type="submit" disabled={processing} className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                            {processing ? 'Saving...' : 'Save Settings'}
                        </button>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
};

export default SettingsForm;
