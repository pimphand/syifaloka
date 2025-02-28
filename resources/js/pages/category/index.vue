<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Categories } from '@/types';
import { Head } from '@inertiajs/vue3';
import AppPagination from '@/components/AppPagination.vue';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { ref } from 'vue'; // Ensure you import the Button component
import { router } from '@inertiajs/vue3'

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

const handleEdit = (id: number) => {
    console.log(`Edit category with ID: ${id}`);
};
const currentPage = ref(1)
const handleDelete = (id: number) => {
    console.log(`Delete category with ID: ${id}`);
};
function updatePage(page: number) {
    currentPage.value = page

    // Fetch data & update URL tanpa reload
    router.get(route('categories.index'), { page }, {
        preserveScroll: true, // Agar tidak scroll ke atas saat ganti halaman
        preserveState: true, // Agar state tidak di-reset
    })
}

</script>

<template>
    <Head title="Kategori" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-5">
            <div class="grid auto-rows-min md:grid-cols-1 border rounded-xl border-sidebar-border/70 dark:border-sidebar-border">
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
                            <TableCell class="font-medium text-center" :colspan="tableHead.length">
                                Tidak ada data
                            </TableCell>
                        </TableRow>
                        <TableRow v-for="(category, index) in categories.data" :key="(category as any).id">
                            <TableCell>{{ (categories as any).from + index }}</TableCell>
                            <TableCell>{{ (category as any).name }}</TableCell>
                            <TableCell>{{ (category as any).icon }}</TableCell>
                            <TableCell>
                                <Button class="m-1 btn bg-sky-600 text-white" variant="outline" @click="handleEdit((category as any).id)" >Edit</Button>
                                <Button class="m-1" variant="destructive" @click="handleDelete((category as any).id)" >Hapus</Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="flex justify-center text-center mt-5 mb-4">
                    <AppPagination :items="categories" :page="currentPage" @update:page="updatePage"  />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
