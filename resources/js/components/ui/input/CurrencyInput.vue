<script setup>
import { ref, watch } from 'vue';
import Input from '@/components/ui/input/Input.vue';

const props = defineProps({
    modelValue: [Number, String],
    placeholder: String,
    class: String
});

const emit = defineEmits(['update:modelValue']);

// Estado local para lo que se ve en pantalla
const displayValue = ref('');
const isFocused = ref(false);

// Función para dar formato: 1234.5 -> "1,234.50"
const format = (value) => {
    if (value === null || value === undefined || value === '' || value === 0) return '';
    const num = typeof value === 'string' ? parseFloat(value) : value;
    if (isNaN(num)) return '';

    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(num);
};

// Función para limpiar: "1,234.50" -> 1234.5
const parse = (value) => {
    if (!value) return null;
    const clean = value.toString().replace(/[^0-9.]/g, '');
    const parsed = parseFloat(clean);
    return isNaN(parsed) ? null : parsed;
};

// Función para formatear mientras se escribe (con comas pero sin forzar decimales)
const formatWhileTyping = (value) => {
    if (!value) return '';

    // Separar parte entera y decimal
    const parts = value.toString().split('.');
    const integerPart = parts[0].replace(/[^0-9]/g, '');
    const decimalPart = parts[1] || '';

    // Formatear la parte entera con comas
    const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    // Retornar con punto decimal si existe
    return decimalPart !== undefined && parts.length > 1
        ? `${formattedInteger}.${decimalPart}`
        : formattedInteger;
};

// Al iniciar o cambiar el valor externo
watch(() => props.modelValue, (newValue) => {
    if (!isFocused.value) {
        displayValue.value = format(newValue);
    }
}, { immediate: true });

// Eventos
const handleInput = (e) => {
    const val = e.target.value;
    const cursorPos = e.target.selectionStart;
    const oldLength = displayValue.value.length;

    // Aplicar formato mientras escribe
    displayValue.value = formatWhileTyping(val);

    // Ajustar posición del cursor después del formato
    const newLength = displayValue.value.length;
    const diff = newLength - oldLength;

    // Restaurar posición del cursor
    setTimeout(() => {
        e.target.setSelectionRange(cursorPos + diff, cursorPos + diff);
    }, 0);

    // Emitir valor numérico limpio
    emit('update:modelValue', parse(val));
};

const handleBlur = () => {
    isFocused.value = false;
    // Al salir, formato completo con decimales
    displayValue.value = format(props.modelValue);
};

const handleFocus = (e) => {
    isFocused.value = true;
    // Al entrar, mostrar con comas pero sin forzar decimales
    if (props.modelValue) {
        displayValue.value = formatWhileTyping(props.modelValue.toString());
        e.target.select();
    }
};
</script>

<template>
    <Input
        type="text"
        :class="props.class"
        :placeholder="placeholder"
        :model-value="displayValue"
        @input="handleInput"
        @blur="handleBlur"
        @focus="handleFocus"
    />
</template>
