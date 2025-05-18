<template>
    <div class="flex h-screen overflow-hidden">

        <!-- Category Modal -->
        <transition name="fade">
            <div v-if="categoryModal"
                class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-black/20">
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl p-6 animate-fade-in">
                    <!-- Header Modal -->
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-base font-semibold text-gray-800 dark:text-white">
                            Choose the Category
                        </h1>
                        <button @click="handleCategoryModal"
                            class="cursor-pointer text-gray-500 hover:text-slate-600 text-lg font-bold">
                            &times;
                        </button>
                    </div>

                    <!-- Scrollable Content -->
                    <div class="overflow-y-auto max-h-[60vh]">
                        <!-- Table Header -->
                        <div
                            class="text-center text-sm font-semibold text-slate-800 dark:text-gray-300 border-b pb-2 mb-2">
                            <div>Name</div>
                        </div>
                        <!-- Table Rows -->
                        <div v-for="cat in categories" :key="cat.id" @click="chooseCategory(cat)"
                            class="text-center text-sm items-center border rounded-lg px-4 py-2 mb-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                            <div class="text-gray-600 dark:text-white">{{ cat.name }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Modal -->
        <ModalForm v-model:visible="showModal" :title="isEdit ? `Edit ${activeTab}` : `Add New ${activeTab}`"
            :submitText="isEdit ? 'Update' : 'Save'"
            :submitClass="isEdit ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-slate-600 hover:bg-slate-700'">
            <template #add-form v-if="activeTab == 'Post'">
                <!-- Choose Category Button -->
                <div class="block">
                    <button @click="handleCategoryModal"
                        class="cursor-pointer text-sm bg-teal-700 text-white px-4 py-2 rounded-lg hover:bg-teal-800 transition">
                        Choose Category
                    </button>

                    <div class="mt-2 text-sm text-gray-700 dark:text-white">
                        <div class="font-medium">Selected:</div>
                        <div>
                            <span v-if="postForm.category" class="text-teal-900">{{ postForm.category.name }}</span>
                            <span v-else class="italic text-gray-400">Not selected yet</span>
                        </div>
                    </div>
                </div>

                <!-- Title Input -->
                <div class="block">
                    <span class="text-sm">Title</span>
                    <input v-model="postForm.title" type="text" placeholder="Title"
                        class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-600 dark:bg-gray-800 dark:text-white disabled:bg-slate-200 dark:disabled:bg-gray-600" />
                </div>

                <!-- Content Textarea (Lebar penuh) -->
                <div class="block sm:col-span-2">
                    <span class="text-sm">Content</span>
                    <textarea v-model="postForm.content" placeholder="Content"
                        class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-600 dark:bg-gray-800 dark:text-white disabled:bg-slate-200 dark:disabled:bg-gray-600"
                        rows="5">
                    </textarea>
                </div>

            </template>

            <template #add-form v-if="activeTab == 'Category'">

                <!-- Title Input -->
                <div class="block">
                    <span class="text-sm">Name</span>
                    <input v-model="categoryForm.name" type="text" placeholder="Name"
                        class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-600 dark:bg-gray-800 dark:text-white disabled:bg-slate-200 dark:disabled:bg-gray-600" />
                </div>

            </template>

            <template #add-button>
                <button @click="onSave()" v-if="!isEdit"
                    class="cursor-pointer text-xs px-4 py-2 rounded-lg text-white bg-teal-600 hover:bg-teal-700">
                    Save
                </button>
                <button @click="onUpdate()" v-else
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
                            <!-- Tabs -->
                            <div class="flex border-b mb-4">
                                <button v-for="tab in tabs" :key="tab" @click="activeTab = tab" :class="[
                                    'px-4 py-2 text-sm font-medium border-b-2 cursor-pointer',
                                    activeTab === tab
                                        ? 'border-slate-700 text-slate-600'
                                        : 'border-transparent text-gray-500 hover:text-slate-900'
                                ]">
                                    {{ tab }}
                                </button>
                            </div>

                            <!-- Title -->
                            <h1 class="text-2xl text-gray-800 dark:text-gray-100 font-bold">
                                {{ activeTab }}
                            </h1>

                        </div>

                    </div>

                    <!-- Body -->

                    <div class="p-4 shadow-xl bg-white rounded-lg">

                        <ListTable v-if="activeTab === 'Post' && posts" :data="posts" :currentPage="postCurrentPage"
                            :pageSize="postPageSize" :totalPages="postTotalPages" :prevPage="postPrevPage"
                            :nextPage="postNextPage" :columns="['category_title', 'title', 'content']"
                            :headerColumns="['category', 'title', 'content', 'Actions']">

                            <template #add-button>
                                <button @click="addPost()"
                                    class="m-3 py-2 cursor-pointer btn bg-teal-900 text-gray-100 hover:bg-teal-800 dark:bg-teal-700 dark:text-gray-100 dark:hover:bg-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M5 13v-1h6V6h1v6h6v1h-6v6h-1v-6z" />
                                    </svg>
                                    <span class="max-xs:sr-only ml-1 text-xs">New</span>
                                </button>
                            </template>

                            <template #button="{ data }">
                                <button @click="editPost(data)"
                                    class="bg-slate-100 cursor-pointer hover:bg-slate-200 shadow-xl p-2 text-white rounded-lg mx-1">
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

                                <button @click="deletePost(data.id)"
                                    class="shadow-xl bg-slate-100 cursor-pointer hover:bg-slate-200 p-2 text-white rounded-lg mx-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        class="fill-red-500">
                                        <path
                                            d="M2 5.27L3.28 4L5 5.72l.28.28l1 1l2 2L16 16.72l2 2l2 2L18.73 22l-1.46-1.46c-.34.29-.77.46-1.27.46H8c-1.1 0-2-.9-2-2V9.27zM8 19h7.73L8 11.27zM18 7v9.18l-2-2V9h-5.18l-2-2zm-2.5-3H19v2H7.82l-2-2H8.5l1-1h5z" />
                                    </svg>

                                </button>
                            </template>

                        </ListTable>

                        <!-- Category -->

                        <ListTable v-if="activeTab === 'Category' && categories" :data="categories"
                            :currentPage="categoryCurrentPage" :pageSize="categoryPageSize"
                            :totalPages="categoryTotalPages" :prevPage="categoryPrevPage" :nextPage="categoryNextPage"
                            :columns="['name',]" :headerColumns="['category', 'Actions']">

                            <template #add-button>
                                <button @click="addCategory()"
                                    class="m-3 py-2 cursor-pointer btn bg-teal-900 text-gray-100 hover:bg-teal-800 dark:bg-teal-700 dark:text-gray-100 dark:hover:bg-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M5 13v-1h6V6h1v6h6v1h-6v6h-1v-6z" />
                                    </svg>
                                    <span class="max-xs:sr-only ml-1 text-xs">New</span>
                                </button>
                            </template>

                            <template #button="{ data }">
                                <button @click="editCategory(data)"
                                    class="bg-slate-100 cursor-pointer hover:bg-slate-200 shadow-xl p-2 text-white rounded-lg mx-1">
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

                                <button @click="deleteCategory(data.id)"
                                    class="shadow-xl bg-slate-100 cursor-pointer hover:bg-slate-200 p-2 text-white rounded-lg mx-1">
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
                        History & Note ({{ activeTab }})
                    </h1>
                    <div class="p-4 shadow-xl bg-white rounded-lg">
                        <ListTable v-if="activeTab === 'Post' && auditPosts" :data="auditPosts"
                            :currentPage="auditPostCurrentPage" :pageSize="auditPageSize"
                            :totalPages="auditPostTotalPages" :prevPage="auditPostPrevPage"
                            :nextPage="auditPostNextPage"
                            :columns="['user', 'event', 'old_values', 'new_values', 'created_at', 'updated_at']"
                            :headerColumns="['User', 'Event', 'Old Values', 'New Values', 'Created At', 'Updated At']">
                        </ListTable>

                        <!-- Category -->

                        <ListTable v-if="activeTab === 'Category' && auditCategories" :data="auditCategories"
                            :currentPage="auditCategoryCurrentPage" :pageSize="auditPageSize"
                            :totalPages="auditCategoryTotalPages" :prevPage="auditCategoryPrevPage"
                            :nextPage="auditCategoryNextPage"
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

/* tabs */
const tabs = ['Post', 'Category']
const activeTab = ref('Post')

/* Category */
const categoryModal = ref(false);

const handleCategoryModal = () => {
    categoryModal.value = !categoryModal.value;
    showModal.value = !showModal.value;
}

/* Category Management */
const categories = ref([]);
const categoryCurrentPage = ref(1)
const categoryTotalPages = ref(1)
const categoryPageSize = 5
const defaultCategoryForm = ref({
    id: '',
    name: '',
})
const categoryForm = ref({ ...defaultCategoryForm })

/* post pagination */
const categoryPrevPage = () => {
    if (categoryCurrentPage.value > 1) categoryCurrentPage.value--
}

const categoryNextPage = () => {
    if (categoryCurrentPage.value < categoryTotalPages.value) categoryCurrentPage.value++
}

const getCategory = async () => {
    loading.value = true;
    try {

        const response = await apiGet('/api/category', {
            page: postCurrentPage, size: postPageSize
        });

        const result = response.data;
        categories.value = result.map(res => ({
            id: res.id,
            name: res.name,
        }));
        categoryTotalPages.value = Math.ceil(result.length / categoryPageSize);
    } catch (error) {
        console.error('error getCategory: ', error)
    } finally {
        loading.value = false;
    }
}

const clearCategoryForm = () => {
    categoryForm.value = { ...defaultCategoryForm };
}

const addCategory = () => {
    showModal.value = !showModal.value;
    clearCategoryForm();
}

const postCategory = async () => {
    loading.value = true;
    try {

        const response = await apiPost('/api/category/create', {
            'name': categoryForm.value.name,
        }, {
            useAuth: true
        });
        if (response) {
            alert.message = "Successfully added new category.";
            alert.type = "success";
            alert.visible = true;
            addCategory();
            reloadFunctions();
        }

    } catch (error) {
        alert.type = 'error';
        alert.message = error;
        alert.visible = true;
        console.error('error postCategory: ', error)
    } finally {
        loading.value = false;
    }
}

const editCategory = (data) => {
    categoryForm.value = { ...data }
    isEdit.value = true;
    showModal.value = true;
}

const updateCategory = async () => {
    loading.value = true;
    try {

        const response = await apiPut(`/api/category/edit/${categoryForm.value.id}/`, {
            'name': categoryForm.value.name,
        }, {
            useAuth: true
        });
        if (response) {
            alert.message = "Successfully edited category.";
            alert.type = "success";
            alert.visible = true;
            addCategory();
            reloadFunctions();
        }

    } catch (error) {
        alert.type = 'error';
        alert.message = error;
        alert.visible = true;
        console.error('error updateCategory: ', error)
    } finally {
        loading.value = false;
    }
}

const deleteCategory = async (id) => {
    const confirmed = await confirmDialog.value.openDialog(
        'Delete Confirmation',
        'Are you sure you want to delete this post?'
    )
    if (!confirmed) return

    loading.value = true

    try {
        const response = await apiDelete(`/api/category/delete/${id}/`)
        if (response) {
            alert.type = 'error';
            alert.message = "Category has been deleted";
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

/* Post management */

const postCurrentPage = ref(1)
const postTotalPages = ref(1)
const postPageSize = 5
const posts = ref([])
const defaultPostForm = ref({
    id: '',
    category: [],
    category_title: '',
    title: '',
    content: '',
})
const postForm = ref({ ...defaultPostForm })

/* post pagination */
const postPrevPage = () => {
    if (postCurrentPage.value > 1) postCurrentPage.value--
}

const postNextPage = () => {
    if (postCurrentPage.value < postTotalPages.value) postCurrentPage.value++
}

/* actions */
const chooseCategory = (cat) => {
    postForm.value.category = cat;
    categoryModal.value = !categoryModal.value;
    showModal.value = !showModal.value;
}

const getPost = async () => {
    loading.value = true;
    try {

        const response = await apiGet('/api/post', {
            page: postCurrentPage, size: postPageSize
        });

        const result = response.data;
        posts.value = result.map(res => ({
            id: res.id,
            category_title: res.category.name,
            category: res.category,
            title: res.title,
            content: res.content,
        }));

        postTotalPages.value = Math.ceil(result.length / postPageSize);
    } catch (error) {
        console.error('error getPost: ', error)
    } finally {
        loading.value = false;
    }
}

const postPost = async () => {
    loading.value = true;
    try {

        const response = await apiPost('/api/post/create', {
            'category_id': postForm.value.category.id,
            'title': postForm.value.title,
            'content': postForm.value.content,
            'publication_date': moment().format('YYYY-MM-DD')
        }, {
            useAuth: true
        });
        if (response) {
            alert.message = "Successfully added new post.";
            alert.type = "success";
            alert.visible = true;
            reloadFunctions();
        }

    } catch (error) {
        alert.type = 'error';
        alert.message = error;
        alert.visible = true;
        console.error('error getPost: ', error)
    } finally {
        loading.value = false;
    }
}

const updatePost = async () => {
    loading.value = true;
    try {
        const response = await apiPut(`/api/post/edit/${postForm.value.id}/`, {
            'category_id': postForm.value.category.id,
            'title': postForm.value.title,
            'content': postForm.value.content,
            'publication_date': moment().format('YYYY-MM-DD')
        }, {
            useAuth: true
        });
        if (response) {
            alert.message = "Successfully edited post.";
            alert.type = "success";
            alert.visible = true;
            reloadFunctions();
        }

    } catch (error) {
        alert.type = 'error';
        alert.message = error;
        alert.visible = true;
        console.error('error updatePost: ', error)
    } finally {
        loading.value = false;
    }
}

const deletePost = async (id) => {
    const confirmed = await confirmDialog.value.openDialog(
        'Delete Confirmation',
        'Are you sure you want to delete this post?'
    )
    if (!confirmed) return

    loading.value = true

    try {
        const response = await apiDelete(`/api/post/delete/${id}/`)
        if (response) {
            alert.type = 'error';
            alert.message = "Post has been deleted";
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

const auditPostCurrentPage = ref(1)
const auditPostTotalPages = ref(1)
const auditPageSize = 5
const auditPosts = ref([])

const auditPostPrevPage = () => {
    if (auditPostCurrentPage.value > 1) auditPostCurrentPage.value--
}

const auditPostNextPage = () => {
    if (auditPostCurrentPage.value < auditPostTotalPages.value) auditPostCurrentPage.value++
}

const auditPost = async () => {
    loading.value = true;
    try {

        const response = await apiGet('/api/audits/post', {
            page: auditPostCurrentPage, size: auditPageSize, useAuth: true
        });

        const result = response;
        auditPosts.value = result.map(res => ({
            user: res.user.name,
            event: res.event,
            old_values: res.old_values,
            new_values: res.new_values,
            created_at: formatDate(res.created_at),
            updated_at: formatDate(res.updated_at),
        }));

        auditPostTotalPages.value = Math.ceil(result.length / auditPageSize);
    } catch (error) {
        console.error('error auditPost: ', error)
    } finally {
        loading.value = false;
    }
}

const auditCategoryCurrentPage = ref(1)
const auditCategoryTotalPages = ref(1)
const auditCategories = ref([])

const auditCategoryPrevPage = () => {
    if (auditCategoryCurrentPage.value > 1) auditCategoryCurrentPage.value--
}

const auditCategoryNextPage = () => {
    if (auditCategoryCurrentPage.value < auditCategoryTotalPages.value) auditCategoryCurrentPage.value++
}

const auditCategory = async () => {
    loading.value = true;
    try {

        const response = await apiGet('/api/audits/category', {
            page: auditCategoryCurrentPage, size: auditPageSize, useAuth: true
        });

        const result = response;
        auditCategories.value = result.map(res => ({
            user: res.user.name,
            event: res.event,
            old_values: res.old_values,
            new_values: res.new_values,
            created_at: formatDate(res.created_at),
            updated_at: formatDate(res.updated_at),
        }));

        auditCategoryTotalPages.value = Math.ceil(result.length / auditPageSize);
    } catch (error) {
        console.error('error auditCategory: ', error)
    } finally {
        loading.value = false;
    }
}


/* form handle */
const addPost = () => {
    showModal.value = !showModal.value;
    clearPostForm();
}

const editPost = (data) => {
    postForm.value = { ...data }
    isEdit.value = true;
    showModal.value = true;
}

const onSave = async () => {
    if (activeTab.value == 'Post') {
        postPost();
    } else {
        postCategory();
    }
}

const onUpdate = async () => {
    if (activeTab.value == 'Post') {
        updatePost();
    } else {
        updateCategory();
    }
}

const clearPostForm = () => {
    postForm.value = { ...defaultPostForm };
}

const reloadFunctions = () => {
    getCategory();
    getPost();
    auditPost();
    auditCategory();
}

onMounted(() => {
    getCategory();
    getPost();
    auditPost();
    auditCategory();
})

watch(postCurrentPage, () => {
    getPost();
})

watch(categoryCurrentPage, () => {
    getCategory();
})


</script>