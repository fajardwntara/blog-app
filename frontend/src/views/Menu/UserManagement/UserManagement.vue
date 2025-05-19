<template>
    <div class="flex h-screen overflow-hidden">
        <!-- Modal -->
        <ModalForm v-model:visible="showModal" :title="isEdit ? `Edit User` : `Add New User`"
            :submitText="isEdit ? 'Update' : 'Save'"
            :submitClass="isEdit ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-teal-600 hover:bg-teal-700'">
            <template #add-form>

                <div class="block">
                    <span class="text-sm">Email</span>
                    <input v-model="userForm.email" type="email" placeholder="Email"
                        class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600 dark:bg-gray-800 dark:text-white disabled:bg-teal-200 dark:disabled:bg-gray-600" />
                </div>

                <div class="block">
                    <span class="text-sm">Name</span>
                    <input v-model="userForm.name" type="text" placeholder="Name"
                        class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600 dark:bg-gray-800 dark:text-white disabled:bg-teal-200 dark:disabled:bg-gray-600" />
                </div>

                <div class="block">
                    <span class="text-sm">Password</span>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" v-model="userForm.password"
                            placeholder="Password"
                            class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600 dark:bg-gray-800 dark:text-white disabled:bg-teal-200 dark:disabled:bg-gray-600" />
                        <button type="button" @click="togglePassword"
                            class="absolute right-3 top-2 text-sm text-gray-500">
                            {{ showPassword ? 'Hide' : 'Show' }}
                        </button>
                    </div>
                </div>
                <div class="block">
                    <span class="text-sm">Password Confirmation</span>
                    <div class="relative">
                        <input :type="showPasswordConfirmation ? 'text' : 'password'"
                            v-model="userForm.password_confirmation" placeholder="Password"
                            class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600 dark:bg-gray-800 dark:text-white disabled:bg-teal-200 dark:disabled:bg-gray-600" />
                        <button type="button" @click="togglePasswordConfirmation"
                            class="absolute right-3 top-2 text-sm text-gray-500">
                            {{ showPasswordConfirmation ? 'Hide' : 'Show' }}
                        </button>
                    </div>
                </div>

                <div class="block">
                    <span class="text-sm">Role</span>
                    <select v-model="userForm.role"
                        class="text-xs w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600 dark:bg-gray-800 dark:text-white">
                        <option disabled value="">Select Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Editor">Editor</option>
                    </select>
                </div>


            </template>

            <template #add-button>
                <button @click="registerUser" v-if="!isEdit"
                    class="cursor-pointer text-xs px-4 py-2 rounded-lg text-white bg-teal-600 hover:bg-teal-700">
                    Save
                </button>
                <button @click="updateUser" v-else
                    class="cursor-pointer text-xs px-4 py-2 rounded-lg text-white bg-yellow-600 hover:bg-yellow-700">
                    Update
                </button>
            </template>
        </ModalForm>

        <!-- Sidebar -->
        <Sidebar :sidebarOpen="sidebarOpen" @close-sidebar="sidebarOpen = false" />

        <!-- Content area -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

            <!-- Base Alert -->
            <BaseAlert v-model="alert.visible" :type="alert.type" :message="alert.message" :duration="2000" />
            <!-- ConfirmDialog -->
            <ConfirmDialog ref="confirmDialog" />
            <!-- Site header -->
            <Header :sidebarOpen="sidebarOpen" @toggle-sidebar="sidebarOpen = !sidebarOpen" :show-user-menu="true" />

            <main class="grow">
                <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

                    <!-- Dashboard actions -->
                    <div class="sm:flex sm:justify-between sm:items-center mb-8">
                        <!-- Left: Title -->
                        <div class="mb-4 sm:mb-0">

                            <!-- Title -->
                            <h1 class="text-2xl text-gray-800 dark:text-gray-100 font-bold">
                                Users
                            </h1>

                        </div>

                    </div>

                    <!-- Body -->

                    <div class="p-4 shadow-xl bg-white rounded-lg">

                        <ListTable v-if="users" :data="users" :currentPage="userCurrentPage" :pageSize="userPageSize"
                            :totalPages="userTotalPages" :prevPage="userPrevPage" :nextPage="userNextPage"
                            :columns="['email', 'name', 'role']" :headerColumns="['email', 'name', 'role']">

                            <template #add-button>
                                <button @click="addUser()"
                                    class="m-3 py-2 cursor-pointer btn bg-teal-900 text-gray-100 hover:bg-teal-800 dark:bg-teal-700 dark:text-gray-100 dark:hover:bg-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M5 13v-1h6V6h1v6h6v1h-6v6h-1v-6z" />
                                    </svg>
                                    <span class="max-xs:sr-only ml-1 text-xs">New</span>
                                </button>
                            </template>

                            <template #button="{ data }">
                                <button @click="editUser(data)"
                                    class="bg-gray-100 cursor-pointer hover:bg-gray-200 shadow-xl p-2 text-white rounded-lg mx-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        class="fill-yellow-700">
                                        <g>
                                            <path
                                                d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z" />
                                            <path
                                                d="M13 3a1 1 0 0 1 .117 1.993L13 5H5v14h14v-8a1 1 0 0 1 1.993-.117L21 11v8a2 2 0 0 1-1.85 1.995L19 21H5a2 2 0 0 1-1.995-1.85L3 19V5a2 2 0 0 1 1.85-1.995L5 3zm6.243.343a1 1 0 0 1 1.497 1.32l-.083.095l-9.9 9.899a1 1 0 0 1-1.497-1.32l.083-.094z" />
                                        </g>
                                    </svg>
                                </button>

                                <button @click="deleteUser(data.id)"
                                    class="shadow-xl bg-gray-100 cursor-pointer hover:bg-gray-200 p-2 text-white rounded-lg mx-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        class="fill-red-500">
                                        <path
                                            d="M2 5.27L3.28 4L5 5.72l.28.28l1 1l2 2L16 16.72l2 2l2 2L18.73 22l-1.46-1.46c-.34.29-.77.46-1.27.46H8c-1.1 0-2-.9-2-2V9.27zM8 19h7.73L8 11.27zM18 7v9.18l-2-2V9h-5.18l-2-2zm-2.5-3H19v2H7.82l-2-2H8.5l1-1h5z" />
                                    </svg>

                                </button>
                            </template>

                        </ListTable>

                    </div>

                    <h1 class="text-lg text-gray-800 dark:text-gray-100 font-bold mt-18">
                        History & Note (User)
                    </h1>
                    <div class="p-4 shadow-xl bg-white rounded-lg">
                        <ListTable v-if="auditUsers" :data="auditUsers" :currentPage="auditUserCurrentPage"
                            :pageSize="auditPageSize" :totalPages="auditUserTotalPages" :prevPage="auditUserPrevPage"
                            :nextPage="auditUserNextPage"
                            :columns="['user', 'event', 'old_values', 'new_values', 'created_at', 'updated_at']"
                            :headerColumns="['User', 'Event', 'Old Values', 'New Values', 'Created At', 'Updated At']">
                        </ListTable>

                    </div>

                </div>
            </main>

        </div>

        <Loading v-if="loading" />
    </div>
</template>

<script setup>
import { onMounted, ref, watch, reactive } from 'vue'
import Sidebar from '@/components/partials/Sidebar.vue'
import Header from '@/components/partials/Header.vue'
import { apiDelete, apiGet, apiPost, apiPut } from '@/lib/axios'
import ListTable from '@/components/ui/ListTable.vue';
import ModalForm from '@/components/ui/ModalForm.vue'
import moment from 'moment';
import { formatDate } from '@/utils/Utils';
import { update } from 'lodash-es';

const sidebarOpen = ref(false)
const confirmDialog = ref(null)
const loading = ref(false);
const isEdit = ref(false)
const showModal = ref(false);
const alert = reactive({
    visible: false,
    type: 'success',
    message: '',
})

const showPassword = ref(false)
const showPasswordConfirmation = ref(false)

const togglePassword = () => {
    showPassword.value = !showPassword.value
}

const togglePasswordConfirmation = () => {
    showPasswordConfirmation.value = !showPasswordConfirmation.value
}

const userPageSize = 5

const userCurrentPage = ref(1)
const userTotalPages = ref(1)
const users = ref([])
const form = ref({
    id: '',
    email: '',
    name: '',
    role: '',
    password: '',
    password_confirmation: '',
})
const userForm = ref({ ...form.value })

const userPrevPage = () => {
    if (userCurrentPage.value > 1) userCurrentPage.value--
}

const userNextPage = () => {
    if (userCurrentPage.value < userTotalPages.value) userCurrentPage.value++
}

const getUser = async () => {
    loading.value = true;
    try {

        const response = await apiGet('/api/user/all', {
            page: userCurrentPage, size: userPageSize
        });

        const result = response;

        users.value = result.map(res => ({
            id: res.id,
            email: res.email,
            name: res.name,
            role: res.role,
        }));

        userTotalPages.value = Math.ceil(result.length / userPageSize);
    } catch (error) {
        console.error('error getUser: ', error)
    } finally {
        loading.value = false;
    }
}

const registerUser = async () => {
    loading.value = true;
    try {

        if (userForm.value.password == !userForm.value.password_confirmation) {
            alert.message = "Password and Password confirmation do not match.";
            alert.type = "info";
            alert.visible = true;
            return
        }

        const response = await apiPost('/api/user/register', {
            'email': userForm.value.email,
            'name': userForm.value.name,
            'password': userForm.value.password,
            'password_confirmation': userForm.value.password_confirmation,
            'role': userForm.value.role,
        }, {
            useAuth: true
        });
        if (response) {
            alert.message = "Successfully added new user.";
            alert.type = "success";
            alert.visible = true;
            reloadFunctions();
        }

    } catch (error) {
        alert.type = 'error';
        alert.message = error;
        alert.visible = true;
        console.error('error create a user: ', error)
    } finally {
        loading.value = false;
    }
}

const updateUser = async () => {
    loading.value = true;

    if (userForm.value.password != userForm.value.password_confirmation) {
        console.log("sene")
        alert.message = "Password and Password Confirmation do not match.";
        alert.type = "error";
        alert.visible = true;
        loading.value = false;
        return;
    }
    console.log("userForm.value : ", userForm.value)
    try {
        const response = await apiPut(`/api/user/update/${userForm.value.id}/`, {
            'email': userForm.value.email,
            'name': userForm.value.name,
            'password': userForm.value.password,
            'password_confirmation': userForm.value.password_confirmation,
            'role': userForm.value.role,
        }, {
            useAuth: true
        });
        if (response) {
            alert.message = "Successfully edited user.";
            alert.type = "success";
            alert.visible = true;
            reloadFunctions();
        }

    } catch (error) {
        alert.type = 'error';
        alert.message = error;
        alert.visible = true;
        console.error('error update user: ', error)
    } finally {
        loading.value = false;
    }
}

const deleteUser = async (id) => {
    console.log("ID : ", id)
    const confirmed = await confirmDialog.value.openDialog(
        'Delete Confirmation',
        'Are you sure you want to delete this user?'
    )
    if (!confirmed) return

    loading.value = true

    try {
        const response = await apiDelete(`/api/user/delete/${id}/`)
        if (response) {
            alert.type = 'error';
            alert.message = "User has been deleted";
            alert.visible = true;
            reloadFunctions();
        }
    } catch (error) {
        alert.type = 'error';
        alert.message = error;
        alert.visible = true;
        console.error(error)
    } finally {
        loading.value = false;
    }
}


// Audit

const auditUserCurrentPage = ref(1)
const auditUserTotalPages = ref(1)
const auditUsers = ref([])
const auditPageSize = 5
const auditUserPrevPage = () => {
    if (auditUserCurrentPage.value > 1) auditUserCurrentPage.value--
}

const auditUserNextPage = () => {
    if (auditUserCurrentPage.value < auditUserTotalPages.value) auditUserCurrentPage.value++
}

const auditUser = async () => {
    loading.value = true;
    try {

        const response = await apiGet('/api/audits/user', {
            page: auditUserCurrentPage, size: userPageSize, useAuth: true
        });
        console.log("response : ", response)
        
        const result = response;
        auditUsers.value = result.map(res => ({
            user: res.user?.name || 'Unknown',
            event: res.event,
            old_values: res.old_values,
            new_values: res.new_values,
            created_at: formatDate(res.created_at),
            updated_at: formatDate(res.updated_at),
        }));

        auditUserTotalPages.value = Math.ceil(result.length / userPageSize);
    } catch (error) {
        console.error('error audit User: ', error)
    } finally {
        loading.value = false;
    }
}

/* form handle */

const addUser = () => {
    showModal.value = !showModal.value;
}

const editUser = (data) => {
    console.log("DATA : ", data)
    userForm.value = { ...data }
    isEdit.value = true;
    showModal.value = true;
}

const reloadFunctions = () => {
    getUser();
    auditUser();
}

onMounted(() => {
    getUser();
    auditUser();
})

watch(userCurrentPage, () => {
    getUser();
})


</script>