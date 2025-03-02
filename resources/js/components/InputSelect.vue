<script setup lang="ts">
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'

defineProps<{
    data: { id: string | number; name: string }[],
    placeholder: string,
}>()
</script>

<template>
    <Select>
        <SelectTrigger class="w-full">
            <SelectValue :placeholder="'Pilih ' + placeholder " />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem v-for="item in data" :key="item.id" :value="item.id">
                    {{ item.name }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
