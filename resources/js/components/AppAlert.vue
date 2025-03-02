<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog'
import { Button } from '@/components/ui/button';
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'

const props = defineProps({
    url: String,
    id:String,
    title: String,
    description: String,
})

const emit = defineEmits(['update:show', 'confirm'])

const closeDialog = () => {
    emit('update:show', false)
}
const form = useForm({
    id: props.id
});
const handleDelete = () => {
    const url = props.url + '/' + form.id
    form.delete(url, {
        onSuccess: () => {
            Swal.fire({
                title: 'Berhasil',
                text: 'Data berhasil dihapus',
                icon: 'success',
            })
        }
    });
};

</script>

<template>
    <AlertDialog @close="closeDialog">
        <AlertDialogTrigger as-child>
            <Button variant="outline" class="bg-red-600 hover:divide-red-300 text-white rounded">Delete</Button>
        </AlertDialogTrigger>
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{props.title}}</AlertDialogTitle>
                <AlertDialogDescription>
                    {{props.description}}
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Batal</AlertDialogCancel>
                <AlertDialogAction @click="handleDelete">Hapus</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
