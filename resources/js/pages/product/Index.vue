<script setup lang="ts">
import AppAlert from '@/components/AppAlert.vue';
import AppPagination from '@/components/AppPagination.vue';
import InputError from '@/components/InputError.vue';
import InputSelect from '@/components/InputSelect.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, Categories } from '@/types';
import { TransitionRoot } from '@headlessui/vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { computed, ref, watch } from 'vue';
import Variant from '@/pages/product/Variant.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Produk', href: '/dashboard' },
];

const tableHead = ['No', 'Foto', 'Kategori', 'Nama Produk', 'Total Varian', 'Aksi'];
const currentPage = ref(1);
const open = ref(false);

const props = defineProps<{
    products: { data: Categories[]; meta: any };
    categories: any;
}>();
const form = useForm({
    name: '',
    category_id: '',
    id: '',
    description: '',
    sku: [],
});

const handleVariantData = (skuData: any) => {
    form.sku = skuData
}

const formErrors = computed(() => form.errors);

const handleEdit = (data: any) => {
    form.name = data.name;
    form.id = data.id;
    open.value = true;
};

const handleSuccess = () => {
    form.reset();
    open.value = false;
    Swal.fire({ title: 'Berhasil', text: 'Data berhasil disimpan', icon: 'success' });
};

const submit = () => {
    const formData = new FormData()
    formData.append('name', form.name)
    formData.append('category_id', form.category_id)
    formData.append('description', form.description)

    // Looping SKU dan menambahkan ke FormData
    form.sku.forEach((sku, index) => {
        formData.append(`sku[${index}][name]`, sku.name)
        formData.append(`sku[${index}][price]`, sku.price)
        formData.append(`sku[${index}][stock]`, sku.stock)
        if (sku.image) {
            formData.append(`sku[${index}][image]`, sku.image)
        }
    })

    form.post(route('products.store'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            handleSuccess()
        },
        onError: () => {
            Swal.fire({ title: 'Gagal', text: 'Data gagal disimpan', icon: 'error' });
        },
    })
};

const updatePage = (page: number) => {
    currentPage.value = page;
    router.get(route('products.index'), { page }, { preserveScroll: true, preserveState: true });
};

const search = () => {
    const category = (document.querySelector('.category') as HTMLInputElement)?.value || '';
    const product = (document.querySelector('.product') as HTMLInputElement)?.value || '';
    router.get(route('products.index'), { category, product }, { preserveScroll: true, preserveState: true });
};
watch(() => form.sku, (newVal) => {
    console.log("Parent SKU Updated:", newVal)
}, { deep: true })
</script>
<style scoped>
.product-image {
    width: 100px;
    max-width: 100%;
    max-height: 100px;
    object-fit: contain;
}
</style>
<template>
    <Head title="Produk" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div v-if="open" class="flex h-full flex-1 flex-col gap-4 p-5">
            <div class="grid auto-rows-min rounded-xl border border-sidebar-border/10 dark:border-sidebar-border md:grid-cols-1">
                <form class="flex flex-col gap-4 space-y-6 p-4" @submit.prevent="submit">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="grid gap-2">
                            <Input hidden v-model="form.id" style="display: none" />
                            <Label for="name">Kategori</Label>
                            <InputSelect :data="props.categories" v-model="form.category_id" :placeholder="'Kategori Produk'" />
                            <InputError class="mt-2" :message="formErrors.category_id" />
                        </div>
                        <div class="grid gap-2">
                            <Input hidden v-model="form.id" style="display: none" />
                            <Label for="name">Name</Label>
                            <Input id="name" v-model="form.name" required autocomplete="name" placeholder="name" />
                            <InputError class="mt-2" :message="formErrors.name" />
                        </div>
                        <div class="grid gap-2">
                            <Input hidden v-model="form.id" style="display: none" />
                            <Label for="name">Deskripsi</Label>
                            <Input id="description" v-model="form.description" required autocomplete="description" placeholder="Deskripsi singkat" />
                            <InputError class="mt-2" :message="formErrors.description" />
                        </div>
                    </div>
                    <hr>
                    <Variant @sendData="handleVariantData" />
                    <div class="flex items-center justify-between">
                        <div>
                            <Button :disabled="form.processing">Save</Button>
                            <Button type="button" class="ml-2 bg-red-500 text-white" @click="open = false">Cancel </Button>
                        </div>
                        <TransitionRoot
                            :show="form.recentlySuccessful"
                            enter="transition ease-in-out"
                            enter-from="opacity-0"
                            leave="transition ease-in-out"
                            leave-to="opacity-0"
                        >
                            <p class="text-sm text-neutral-600">Data berhasil disimpan</p>
                        </TransitionRoot>
                    </div>
                </form>
            </div>
        </div>

        <div v-else class="flex h-full flex-1 flex-col gap-4 p-5">
            <div class="grid auto-rows-min rounded-xl border border-sidebar-border/10 dark:border-sidebar-border md:grid-cols-1">
                <div class="grid grid-cols-3 items-center gap-4 p-4">
                    <Input class="category" @input.prevent="search" placeholder="Masukkan nama kategori" />
                    <Input class="product" @input.prevent="search" placeholder="Masukkan nama produk" />
                    <Button @click="open = true">Tambah</Button>
                </div>
            </div>
            <div class="grid auto-rows-min rounded-xl border border-sidebar-border/10 dark:border-sidebar-border md:grid-cols-1">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead v-for="head in tableHead" :key="head">{{ head }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-if="props.products.data.length === 0">
                            <TableCell class="text-center font-medium" :colspan="tableHead.length">Tidak ada data </TableCell>
                        </TableRow>
                        <TableRow v-for="(product, index) in props.products.data" :key="(product as any).id">
                            <TableCell>{{ (props.products as any).from + index }}</TableCell>
                            <TableCell><img class="product-image" :src="(product as any).image" :alt="(product as any).category.name" /></TableCell>
                            <TableCell>{{ (product as any).category.name }}</TableCell>
                            <TableCell>{{ (product as any).name }}</TableCell>
                            <TableCell>{{ (product as any).skus_count }} Varian</TableCell>
                            <TableCell>
                                <Button class="m-1 bg-sky-600 text-white hover:bg-sky-300" @click="handleEdit(product)"> Edit </Button>
                                <AppAlert
                                    :show="true"
                                    :id="(product as any).id"
                                    :url="route('products.index')"
                                    :title="'Hapus data ' + (product as any).name"
                                    :description="'Data yang dihapus tidak dapat dikembalikan'"
                                />
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="mb-4 mt-5 flex justify-center">
                <AppPagination :items="props.products" :page="currentPage" @update:page="updatePage" />
            </div>
        </div>
    </AppLayout>
</template>
