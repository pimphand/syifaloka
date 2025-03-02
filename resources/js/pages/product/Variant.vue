<template>
    <h4>Sku / Variant</h4>

    <div class="grid grid-cols-3 gap-4" v-for="(sku, index) in skuList" :key="index">
        <div class="grid gap-2">
            <Label :for="'sku_name_' + index">Name</Label>
            <Input :id="'sku_name_' + index" v-model="sku.name" required placeholder="Name" />
        </div>
        <div class="grid gap-2">
            <Label :for="'sku_price_' + index">Price</Label>
            <Input :id="'sku_price_' + index" v-model="sku.price" required placeholder="Price" />
        </div>
        <div class="grid gap-2">
            <Label :for="'sku_stock_' + index">Stock</Label>
            <Input :id="'sku_stock_' + index" v-model="sku.stock" required placeholder="Stock" />
        </div>
        <div class="grid gap-2">
            <Label :for="'sku_image_' + index">Foto</Label>
            <Input type="file" :id="'sku_image_' + index" @change="handleFileUpload($event, index)" />
            <InputError class="mt-2" :id="'error_sku_image_'+index" />
        </div>
        <button
           type="button"
            v-if="skuList.length > 1"
            @click="removeSku(index)"
            class="bg-red-500 text-white px-4 py-2 rounded"
        >
            Hapus
        </button>
    </div>

    <Button @click="addSku" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">
        Tambah SKU
    </Button>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';


const emit = defineEmits(['sendData'])

const skuList = ref([
    {
        name: '',
        price: '',
        stock: '',
        image: null,
    },
])

const addSku = () => {
    skuList.value.push({
        name: '',
        price: '',
        stock: '',
        image: null,
    })
}

const removeSku = (index: number) => {
    skuList.value.splice(index, 1)
}

const handleFileUpload = (event: Event, index: number) => {
    const file = (event.target as HTMLInputElement).files?.[0]
    if (file) {
        skuList.value[index].image = file
        console.log('File Uploaded:', file.size)
        if (file.size > 1024 * 1024 * 2) {
            const error = document.getElementById(`error_sku_image_${index}`)
            if (error) {
                error.innerHTML = 'File maksimal 2MB'
                error.style.color = 'red'
                error.style.display = 'block'
            }
            skuList.value[index].image = null
        }
    }
}

// Kirim data setiap kali ada perubahan pada skuList
watch(skuList, () => {
    emit('sendData', skuList.value)
}, { deep: true })
</script>
