<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { defineProps, ref } from "vue";

defineProps({
    posts: Object,
    now: String,
});

const form = useForm({
    body: "",
});

const createPost = () => {
    form.post("/posts", {
        onSuccess: () => {
            form.reset();
        },
    });
};

const deletePost = (postId) => {
    if (confirm("削除していいですか？")) {
        form.delete(route("posts.destroy", postId), {
            onSuccess: () => {
                refreshPosts();
            },
        });
    }
};

const editForm = useForm({
    body: "",
});

const editingPost = ref(null);

const editPost = (post) => {
    editingPost.value = post;
    editForm.body = post.body;
};

const updatePost = () => {
    editForm.put(route("posts.update", { post: editingPost.value.id }), {
        onSuccess: () => {
            editingPost.value = null;
            editForm.reset();
            refreshPosts();
        },
        preserveScroll: true,
    });
};

const refreshPosts = () => {
    form.get(
        route("posts.index"),
        {},
        {
            only: ["posts", "now"],
            preserveScroll: true,
        }
    );
};
</script>

<template>
    <Head title="Memo">
        <meta name="description" content="memo Index" />
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Memo
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-3">
                <form @submit.prevent="createPost" class="space-y-3">
                    <label for="body" class="sr-only">Body</label>
                    <textarea
                        v-model="form.body"
                        v-on:focus="form.clearErrors('body')"
                        name="body"
                        id="body"
                        cols="30"
                        rows="5"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                    ></textarea>
                    <p v-if="form.errors.body" class="text-red-500">
                        {{ form.errors.body }}
                    </p>
                    <button
                        :disabled="form.processing"
                        type="submit"
                        class="mt-2 bg-gray-700 px-4 py-2 rounded-md font-medium text-white"
                        :class="{ 'opacity-50': form.processing }"
                    >
                        Post
                    </button>
                </form>
                <div v-for="post in posts.data" :key="post.id">
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6 text-gray-900">
                            <!-- <div class="font-semibold">
                                {{ post.user.name }}
                            </div> -->
                            <p class="m-1">{{ post.body }}</p>
                        </div>
                        <div class="flex p-6 bg-gray-100 text-right space-x-2">
                            <button
                                @click="editPost(post)"
                                class="text-sm text-blue-500"
                            >
                                Edit
                            </button>
                            <button
                                @click="deletePost(post.id)"
                                class="text-sm text-red-500"
                            >
                                Delete
                            </button>
                        </div>
                        <div v-if="editingPost && editingPost.id === post.id">
                            <form
                                @submit.prevent="updatePost"
                                class="space-y-3"
                            >
                                <label for="edit-body" class="sr-only"
                                    >Edit Body</label
                                >
                                <textarea
                                    v-model="editForm.body"
                                    v-on:focus="editForm.clearErrors('body')"
                                    name="edit-body"
                                    id="edit-body"
                                    cols="30"
                                    rows="5"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                ></textarea>
                                <p
                                    v-if="editForm.errors.body"
                                    class="text-red-500"
                                >
                                    {{ editForm.errors.body }}
                                </p>
                                <button
                                    :disabled="editForm.processing"
                                    type="submit"
                                    class="mt-2 bg-blue-700 px-4 py-2 rounded-md font-medium text-white"
                                    :class="{
                                        'opacity-50': editForm.processing,
                                    }"
                                >
                                    Update
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
