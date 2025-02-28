<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Categories } from '@/types';

const handleEdit = (id: number) => {
    console.log(`Edit kategori dengan ID: ${id}`);
};

const handleDelete = (id: number) => {
    console.log(`Hapus kategori dengan ID: ${id}`);
};

//props
defineProps<{
    data: { data: Categories[] };
    tableHead: string[];
}>();
</script>

<template>
    <Table>
        <TableCaption></TableCaption>
        <TableHeader>
            <TableRow class="bg-gray-50 dark:bg-gray-800">
                <TableHead class="w-[100px]" v-for="head in tableHead" :key="head">
                    {{ head }}
                </TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow>
                <TableCell class="font-medium" v-if="data.length === 0" colspan="tableHead.length">
                    Tidak ada data
                </TableCell>
                <TableCell></TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
