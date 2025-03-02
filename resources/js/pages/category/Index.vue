<script setup lang="ts">
import AppPagination from '@/components/AppPagination.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input'; // Ensure you import the Button component
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Categories } from '@/types';
import { TransitionRoot } from '@headlessui/vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppAlert from '@/components/AppAlert.vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Kategori',
        href: '/dashboard',
    },
];

const tableHead = ['No', 'Nama Kategori', 'Icon', 'Aksi'];

defineProps<{
    categories: { data: Categories[]; meta: any };
}>();

const handleEdit = (data: any) => {
    form.name = data.name;
    form.icon = data.icon;
    form.id = data.id;
    open.value = true;
};
const currentPage = ref(1);
function updatePage(page: number) {
    currentPage.value = page;

    // Fetch data & update URL tanpa reload
    router.get(
        route('categories.index'),
        { page },
        {
            preserveScroll: true, // Agar tidak scroll ke atas saat ganti halaman
            preserveState: true, // Agar state tidak di-reset
        },
    );
}

function search(e: any) {
    const search = e.target.value;
    router.get(
        route('categories.index'),
        { search },
        {
            preserveScroll: true, // Agar tidak scroll ke atas saat ganti halaman
            preserveState: true, // Agar state tidak di-reset
        },
    );
}

const form = useForm({
    name: '',
    icon: '',
    id: '',
});

const handleSuccess = () => {
    form.reset();
    Swal.fire({
        title: 'Berhasil',
        text: 'Data berhasil disimpan',
        icon: 'success',
    })
    open.value = false;
};

const submit = () => {
    const url = form.id
        ? route('categories.update', { category: form.id })
        : route('categories.store');

    // eslint-disable-next-line @typescript-eslint/no-unused-expressions
    form.id ? form.patch(url, {
        preserveScroll: true,
        onSuccess: handleSuccess,
    }) : form.post(url, {
        preserveScroll: true,
        onSuccess: handleSuccess,
    });
};

const open = ref(false);
</script>

<template>
    <Head title="Kategori" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-5" v-if="open == true">
            <form class="flex flex-col space-y-6" @submit.prevent="submit">
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Input hidden="" v-model="form.id" style="display: none"/>
                        <Label for="name">Name</Label>
                        <Input id="name" v-model="form.name" class="mt-1 block w-full" required autocomplete="name" placeholder="name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="icon">Icon</Label>
                        <Input
                            id="icon"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.icon"
                            autocomplete="icon"
                            placeholder="icon"
                        />
                        <InputError class="mt-2" :message="form.errors.icon" />
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <Button :disabled="form.processing" class="m-1">Save</Button>
                        <Button type="button" class="m-1 bg-red-500 hover:bg-red-300 text-white" @click="open = false">Cancel</Button>
                    </div>
                    <TransitionRoot
                        :show="form.recentlySuccessful"
                        enter="transition ease-in-out"
                        enter-from="opacity-0"
                        leave="transition ease-in-out"
                        leave-to="opacity-0"
                    >
                        <p class="text-sm text-neutral-600">Data berhasil di simpan</p>
                    </TransitionRoot>
                </div>
            </form>
        </div>

        <div class="flex h-full flex-1 flex-col gap-4 p-5" v-if="open == false">
            <div class="grid grid-cols-3 gap-4 items-center">
                <Input class="mt-1 block w-full" @input="search($event)" type="text" placeholder="Masukkan nama kategori" />
                <div class="flex justify-end"></div>
                <Button class="mt-1 ml-auto w-[80px]" @click="open = true" >Tambah</Button>
            </div>

            <div class="grid auto-rows-min rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:grid-cols-1">
                <Table>
                    <TableCaption></TableCaption>
                    <TableHeader>
                        <TableRow class="bg-gray-50 dark:bg-gray-800">
                            <TableHead v-for="head in tableHead" :key="head" class="w-[100px]">
                                {{ head }}
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-if="categories.data.length === 0">
                            <TableCell class="text-center font-medium" :colspan="tableHead.length"> Tidak ada data </TableCell>
                        </TableRow>
                        <TableRow v-for="(category, index) in categories.data" :key="(category as any).id">
                            <TableCell class="p-3">{{ (categories as any).from + index }}</TableCell>
                            <TableCell>{{ (category as any).name }}</TableCell>
                            <TableCell>{{ (category as any).icon }}</TableCell>
                            <TableCell>
                                <Button class="btn m-1 bg-sky-600 text-white" variant="outline" @click="handleEdit((category as any))"
                                >Edit
                                </Button>
                                <AppAlert :show="true"
                                          :id="(category as any).id"
                                          :url="route('categories.index')"
                                          :title="'Hapus data ' + (category as any).name"
                                          :description="'Data yang dihapus tidak dapat dikembalikan'"
                                />
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="mb-4 mt-5 flex justify-center text-center">
                    <AppPagination :items="categories" :page="currentPage" @update:page="updatePage" />
                </div>
            </div>
        </div>

    </AppLayout>
</template>
