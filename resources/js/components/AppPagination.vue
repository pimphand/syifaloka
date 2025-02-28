<script setup lang="ts">
import { ref, watch } from 'vue'
import {
    Button,
} from '@/components/ui/button'

import {
    Pagination,
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
} from '@/components/ui/pagination'
import type { Categories } from '@/types'

// Props menerima data kategori
const props = defineProps<{
    items: { data: Categories[]; meta: { total: number },total: number, per_page: number,last_page_url: number };
}>()

// Emit untuk mengirim event ke parent
const emit = defineEmits<{
    (e: 'update:page', page: number): void;
}>()
// State halaman saat ini ambil query string dari URL

const urlParams = new URLSearchParams(window.location.search);
const url = ref(urlParams.get('page') || 1);


const currentPage = ref(url) // State halaman saat ini

const totalPages = ref(Math.ceil(props.items.total / 10)) // Total halaman

// Fungsi untuk mengubah halaman dan emit ke parent
function handlePageChange(page: number) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
        emit('update:page', page)
    }
}

// Watch perubahan halaman untuk emit (tidak diperlukan jika v-model digunakan)
watch(currentPage, (newPage) => {
    emit('update:page', newPage)
})
</script>

<template>
    <Pagination v-model:page="currentPage"
                :items-per-page="props.items.per_page"
                :total="props.items.total"
                :sibling-count="1"
                show-edges
                :default-page="1">
        <PaginationList v-slot="{ items }" class="flex items-center gap-1">
            <PaginationFirst @click="handlePageChange(1)" />
            <PaginationPrev @click="handlePageChange(currentPage - 1)" />
            <template v-for="(item, index) in items" :key="index">
                <PaginationListItem v-if="item.type === 'page'" :value="item.value" as-child>
                    <Button @click="handlePageChange(item.value)"
                            class="w-10 h-10 p-0"
                            :variant="item.value === currentPage ? 'default' : 'outline'">
                        {{ item.value }}
                    </Button>
                </PaginationListItem>
                <PaginationEllipsis v-else />
            </template>

            <PaginationNext @click="handlePageChange(currentPage + 1)" />
            <PaginationLast @click="handlePageChange(props.items.last_page_url)" />
        </PaginationList>
    </Pagination>
</template>
