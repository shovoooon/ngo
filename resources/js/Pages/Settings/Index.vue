<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const page = usePage();
const settings = page.props.settings || {};

const form = useForm({
    settings: {
        company_name: settings.company_name || '',
        company_email: settings.company_email || '',
        company_phone: settings.company_phone || '',
        company_address: settings.company_address || '',
        currency_symbol: settings.currency_symbol || '৳',
        currency_code: settings.currency_code || 'BDT',
        language: settings.language || 'en',
        timezone: settings.timezone || 'Asia/Dhaka',
        date_format: settings.date_format || 'd/m/Y',
        allow_registration: settings.allow_registration || '1',
    }
});

const submit = () => {
    form.post(route('settings.update'), {
        preserveScroll: true,
        onSuccess: () => alert('Settings updated successfully!'),
    });
};
</script>

<template>
    <AuthenticatedLayout :title="$t('System Settings')">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $t('System Settings') }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- General Settings -->
                            <div class="col-span-1 md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">{{ $t('General Configuration') }}</h3>
                            </div>

                            <div>
                                <InputLabel for="company_name" :value="$t('Company Name')" />
                                <TextInput
                                    id="company_name"
                                    v-model="form.settings.company_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError :message="form.errors['settings.company_name']" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="company_email" :value="$t('Company Email')" />
                                <TextInput
                                    id="company_email"
                                    v-model="form.settings.company_email"
                                    type="email"
                                    class="mt-1 block w-full"
                                />
                                <InputError :message="form.errors['settings.company_email']" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="company_phone" :value="$t('Company Phone')" />
                                <TextInput
                                    id="company_phone"
                                    v-model="form.settings.company_phone"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError :message="form.errors['settings.company_phone']" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <InputLabel for="company_address" :value="$t('Company Address')" />
                                <TextInput
                                    id="company_address"
                                    v-model="form.settings.company_address"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError :message="form.errors['settings.company_address']" class="mt-2" />
                            </div>

                            <!-- Localization Settings -->
                            <div class="col-span-1 md:col-span-2 mt-4">
                                <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">{{ $t('Localization') }}</h3>
                            </div>

                            <div>
                                <InputLabel for="currency_symbol" :value="$t('Currency Symbol')" />
                                <TextInput
                                    id="currency_symbol"
                                    v-model="form.settings.currency_symbol"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError :message="form.errors['settings.currency_symbol']" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="currency_code" :value="$t('Currency Code')" />
                                <TextInput
                                    id="currency_code"
                                    v-model="form.settings.currency_code"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError :message="form.errors['settings.currency_code']" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="language" :value="$t('Language')" />
                                <select id="language" v-model="form.settings.language" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                    <option value="en">{{ $t('English') }}</option>
                                    <option value="bn">{{ $t('Bengali') }}</option>
                                </select>
                                <InputError :message="form.errors['settings.language']" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="timezone" :value="$t('Timezone')" />
                                <select id="timezone" v-model="form.settings.timezone" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                    <option value="Asia/Dhaka">{{ $t('Asia/Dhaka (Bangladesh Time)') }}</option>
                                    <option value="UTC">{{ $t('UTC') }}</option>
                                </select>
                                <InputError :message="form.errors['settings.timezone']" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <PrimaryButton :disabled="form.processing">
                                {{ $t('Save Settings') }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
