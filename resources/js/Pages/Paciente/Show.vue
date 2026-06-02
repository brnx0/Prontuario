<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useMask } from '../../composables/useMask';
import SearchableSelect from '@/Components/SearchableSelect.vue';
import Swal from 'sweetalert2';

const { maskCpf, maskPhone, maskCep } = useMask();

const props = defineProps<{
    paciente?: any;
    medicos: any[];
    enfermeiros: any[];
}>();

const isEditing = !!props.paciente;

const parseNascimento = (nascimento: string | null) => {
    if (!nascimento) return '';
    if (nascimento.includes('-')) {
        const p = nascimento.split('-');
        return p[0].length === 2 ? `${p[2]}-${p[1]}-${p[0]}` : nascimento;
    }
    return nascimento;
};

const form = useForm({
    nome: props.paciente?.nome || '',
    nascimento: parseNascimento(props.paciente?.nascimento || null),
    cpf: props.paciente?.cpf || '',
    cartao_sus: props.paciente?.cartao_sus || '',
    profissao: props.paciente?.profissao || '',
    filicao_1: props.paciente?.filicao_1 || '',
    filicao_2: props.paciente?.filicao_2 || '',
    cep: props.paciente?.cep || '',
    logradouro: props.paciente?.logradouro || '',
    cidade: props.paciente?.cidade || '',
    uf: props.paciente?.uf || '',
    tel_1: props.paciente?.tel_1 || '',
    tel_2: props.paciente?.tel_2 || '',
    email: props.paciente?.email || '',
    situacao: props.paciente?.situacao || '',
    cid10: props.paciente?.cid10 || '',
    mmhg: props.paciente?.mmhg || '',
    bpm: props.paciente?.bpm || '',
    rpm: props.paciente?.rpm || '',
    spo2: props.paciente?.spo2 || '',
    temp: props.paciente?.temp || '',
    kg: props.paciente?.kg || '',
    hgt: props.paciente?.hgt || '',
    alergias: props.paciente?.alergias || '',
    medicamentos: props.paciente?.medicamentos || '',
    descricao_caso: props.paciente?.descricao_caso || '',
    sintomas: props.paciente?.sintomas || '',
    med_cod: props.paciente?.med_cod || '',
    enf_cod: props.paciente?.enf_cod || '',
});

const cepLoading = ref(false);

const onCpfInput = (e: Event) => {
    form.cpf = maskCpf((e.target as HTMLInputElement).value);
};
const onTel1Input = (e: Event) => {
    form.tel_1 = maskPhone((e.target as HTMLInputElement).value);
};
const onTel2Input = (e: Event) => {
    form.tel_2 = maskPhone((e.target as HTMLInputElement).value);
};
const onCepInput = async (e: Event) => {
    const raw = maskCep((e.target as HTMLInputElement).value);
    form.cep = raw;
    const digits = raw.replace(/\D/g, '');
    if (digits.length === 8) {
        cepLoading.value = true;
        try {
            const res = await fetch(`https://viacep.com.br/ws/${digits}/json/`);
            const data = await res.json();
            if (!data.erro) {
                form.logradouro = data.logradouro || '';
                form.cidade = data.localidade || '';
                form.uf = data.uf || '';
            }
        } catch {}
        finally { cepLoading.value = false; }
    }
};

const submitForm = () => {
    if (isEditing) {
        form.put(`/paciente/${props.paciente.pac_cod}`, { preserveScroll: true });
    } else {
        form.post('/paciente', { preserveScroll: true });
    }
};

const save = async () => {
    if (!form.nome || !form.nascimento) {
        submitForm();
        return;
    }

    const params = new URLSearchParams({ nome: form.nome, nascimento: form.nascimento });
    if (isEditing) params.append('excluir_id', props.paciente.pac_cod);

    try {
        const res = await fetch(`/paciente/verificar-duplicata?${params.toString()}`);
        const duplicatas = await res.json();

        if (duplicatas.length === 0) {
            submitForm();
            return;
        }

        const isDark = document.documentElement.classList.contains('dark');

        const lista = duplicatas.map((p: any) => {
            const nascFormatado = p.nascimento
                ? p.nascimento.split('-').reverse().join('/')
                : '-';
            return `<li style="margin-bottom:4px"><b>${p.nome}</b> — Nasc: ${nascFormatado}${p.cpf ? ' — CPF: ' + p.cpf : ''}</li>`;
        }).join('');

        const result = await Swal.fire({
            title: 'Paciente já cadastrado?',
            html: `<p style="margin-bottom:10px">Encontrado(s) <b>${duplicatas.length}</b> registro(s) com o mesmo nome e data de nascimento:</p><ul style="text-align:left;padding-left:16px">${lista}</ul><p style="margin-top:10px">Deseja cadastrar mesmo assim?</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sim, cadastrar assim mesmo',
            cancelButtonText: 'Cancelar',
            background: isDark ? '#1f2937' : '#fff',
            color: isDark ? '#fff' : '#1f2937',
        });

        if (result.isConfirmed) {
            submitForm();
        }
    } catch {
        // Se falhar a verificação, deixa salvar sem bloquear
        submitForm();
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Ficha do Paciente' : 'Novo Paciente'" />
    <AppLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <button @click="router.visit('/paciente')"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-sm font-medium">
                    ← Voltar
                </button>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ isEditing ? 'Ficha do Paciente' : 'Novo Paciente' }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
                    <form @submit.prevent="save">

                        <!-- Identificação -->
                        <div class="mb-8">
                            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                Identificação
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-4">
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nome 
                                    </label>
                                    <input v-model="form.nome" type="text" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                    <p v-if="form.errors.nome" class="mt-1 text-sm text-red-500">{{ form.errors.nome }}</p>
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nascimento 
                                    </label>
                                    <input v-model="form.nascimento" type="date" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                    <p v-if="form.errors.nascimento" class="mt-1 text-sm text-red-500">{{ form.errors.nascimento }}</p>
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">CPF</label>
                                    <input :value="form.cpf" @input="onCpfInput" type="text" maxlength="14"
                                        placeholder="000.000.000-00"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cartão SUS</label>
                                    <input v-model="form.cartao_sus" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profissão</label>
                                    <input v-model="form.profissao" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filiação 1 (Mãe)</label>
                                    <input v-model="form.filicao_1" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filiação 2 (Pai)</label>
                                    <input v-model="form.filicao_2" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Endereço e Contato -->
                        <div class="mb-8">
                            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                Endereço e Contato
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-4">
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        CEP
                                        <span v-if="cepLoading" class="ml-1 text-xs text-blue-500">buscando...</span>
                                    </label>
                                    <input :value="form.cep" @input="onCepInput" type="text" maxlength="9"
                                        placeholder="00000-000"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div class="md:col-span-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logradouro</label>
                                    <input v-model="form.logradouro" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">UF</label>
                                    <input v-model="form.uf" type="text" maxlength="2"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cidade</label>
                                    <input v-model="form.cidade" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone 1</label>
                                    <input :value="form.tel_1" @input="onTel1Input" type="text" maxlength="15"
                                        placeholder="(00) 00000-0000"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone 2</label>
                                    <input :value="form.tel_2" @input="onTel2Input" type="text" maxlength="15"
                                        placeholder="(00) 00000-0000"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">E-mail</label>
                                    <input v-model="form.email" type="email"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-500">{{ form.errors.email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Dados Clínicos -->
                        <div class="mb-8">
                            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                Dados Clínicos
                            </h3>

                            <!-- Médico e Enfermeiro -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <SearchableSelect
                                    v-model="form.med_cod"
                                    :options="medicos"
                                    labelKey="med_nome"
                                    valueKey="med_cod"
                                    placeholder="🔍 Buscar médico(a)..."
                                    label="Médico(a)"
                                />
                                <SearchableSelect
                                    v-model="form.enf_cod"
                                    :options="enfermeiros"
                                    labelKey="enf_nome"
                                    valueKey="enf_cod"
                                    placeholder="🔍 Buscar enfermeiro(a)..."
                                    label="Enfermeiro(a)"
                                />
                            </div>

                            <!-- Situação e CID-10 -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Situação/Queixa</label>
                                    <input v-model="form.situacao" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">CID-10</label>
                                    <input v-model="form.cid10" type="text" maxlength="10" placeholder="Ex: J11, K21.0"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                            </div>

                            <!-- Sinais Vitais -->
                            <div class="grid grid-cols-2 md:grid-cols-7 gap-4 mb-6">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Pressão (mmHg)</label>
                                    <input v-model="form.mmhg" type="text" maxlength="8"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">FC (bpm)</label>
                                    <input v-model="form.bpm" type="text" maxlength="8"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">FR (rpm)</label>
                                    <input v-model="form.rpm" type="text" maxlength="8"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">SPO2 (%)</label>
                                    <input v-model="form.spo2" type="text" maxlength="8"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Temp &deg;C</label>
                                    <input v-model="form.temp" type="text" maxlength="8"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Peso (Kg)</label>
                                    <input v-model="form.kg" type="text" maxlength="8"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">HGT (mg/dl)</label>
                                    <input v-model="form.hgt" type="text" maxlength="8"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                                </div>
                            </div>

                            <!-- Alergias e Medicamentos -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alergias Conhecidas</label>
                                    <textarea v-model="form.alergias" rows="2"
                                        placeholder="Ex: Penicilina, dipirona, látex..."
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medicamentos em Uso Contínuo</label>
                                    <textarea v-model="form.medicamentos" rows="2"
                                        placeholder="Ex: Losartana 50mg, Metformina 850mg..."
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm"></textarea>
                                </div>
                            </div>

                            <!-- Descrição do Caso -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição do Caso Clínico</label>
                                <textarea v-model="form.descricao_caso" rows="4" maxlength="2000"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm"></textarea>
                            </div>

                            <!-- Sintomas -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sintomas</label>
                                <textarea v-model="form.sintomas" rows="4" maxlength="2000"
                                    placeholder="Descreva os sintomas do paciente..."
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm"></textarea>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" :disabled="form.processing"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow disabled:opacity-50 transition duration-150">
                                {{ isEditing ? 'Salvar Alterações' : 'Cadastrar Paciente' }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
