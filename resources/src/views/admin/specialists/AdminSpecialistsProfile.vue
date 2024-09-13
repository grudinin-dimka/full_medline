<template>
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists/{{ $route.params.id }}</template>
	</info-bar>

	<!-- Основные данные врача -->
	<block>
		<block-title>
			<template #title>Профиль</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>
		<div class="test-container">
			<div
				:style="{
					backgroundImage: `url(/storage/slides/lesnikova.png)`,
					height: '300px',
					width: '300px',
					backgroundSize: 'cover',
					backgroundPosition: 'center',
					backgroundRepeat: 'no-repeat',
				}"
			></div>
		</div>
		<container-input>
			<container-input-three>
				<template #title-one>Фамилия</template>
				<template #input-one>
					<input
						type="text"
						v-model="spesialist.profile.family.body"
						placeholder="введите фамилию"
					/>
				</template>
				<template #title-two>Имя</template>
				<template #input-two>
					<input
						type="text"
						v-model="spesialist.profile.name.body"
						placeholder="введите имя"
					/>
				</template>
				<template #title-three>Отчество</template>
				<template #input-three>
					<input
						type="text"
						v-model="spesialist.profile.surname.body"
						placeholder="введите отчество"
					/>
				</template>
			</container-input-three>
			<container-input-two>
				<template #title-one>Начало первой работы</template>
				<template #input-one>
					<input type="date" v-model="spesialist.profile.startWorkAge.body" />
				</template>
				<template #title-two>Город первой работы</template>
				<template #input-two>
					<input
						type="text"
						v-model="spesialist.profile.startWorkCity.body"
						placeholder="введите название города"
					/>
				</template>
			</container-input-two>
			<container-input-two>
				<template #title-one>Взрослый врач</template>
				<template #input-one>
					<select v-model="spesialist.profile.adultDoctor.body">
						<option value="false">Нет</option>
						<option value="true">Да</option>
					</select>
				</template>
				<template #title-two>Детский врач</template>
				<template #input-two>
					<select v-model="spesialist.profile.childrenDoctor.body">
						<option value="false">Нет</option>
						<option value="true">Да</option>
					</select>
				</template>
			</container-input-two>
		</container-input>

		<block-buttons v-if="$route.params.id === 'new'">
			<button-default> Добавить </button-default>
		</block-buttons>
	</block>

	<block>
		<block-title>
			<template #title>Специализации</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<block-buttons>
			<button-default> Добавить </button-default>
		</block-buttons>
	</block>

	<block>
		<block-title>
			<template #title>Клиники</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<block-buttons>
			<button-default> Добавить </button-default>
		</block-buttons>
	</block>

	<block>
		<block-title>
			<template #title>Образование</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<block-buttons>
			<button-default> Добавить </button-default>
		</block-buttons>
	</block>

	<block>
		<block-title>
			<template #title>Место работы</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<block-buttons>
			<button-default> Добавить </button-default>
		</block-buttons>
	</block>

	<block>
		<block-title>
			<template #title>Сертификаты</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<block-buttons v-if="$route.params.id === 'new'">
			<button-default> Добавить </button-default>
		</block-buttons>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/ElementInputLabel.vue";
import Block from "../../../components/ui/admin/Block.vue";
import BlockTitle from "../../../components/ui/admin/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/BlockButtons.vue";
import SpanError from "../../../components/ui/admin/SpanError.vue";

import ContainerInput from "../../../components/ui/admin/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/ContainerInputTwo.vue";
import ContainerInputThree from "../../../components/ui/admin/ContainerInputThree.vue";

import ButtonDefault from "../../../components/ui/admin/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/ButtonRemove.vue";

import IconSave from "../../../components/icons/IconSave.vue";

import axios from "axios";

import { RouterView, RouterLink } from "vue-router";

export default {
	components: {
		InfoBar,
		LoaderChild,
		ElementInputLabel,
		Block,
		BlockTitle,
		BlockButtons,
		SpanError,
		ContainerInput,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputThree,
		ButtonDefault,
		ButtonRemove,
		IconSave,
		axios,
		RouterView,
		RouterLink,
	},
	data() {
		return {
			spesialist: {
				profile: {
					id: 1,
					family: {
						body: "",
						edited: false,
					},
					name: {
						body: "",
						edited: false,
					},
					surname: {
						body: "",
						edited: false,
					},
					startWorkAge: {
						body: "",
						edited: false,
					},
					startWorkCity: {
						body: "",
						edited: false,
					},
					adultDoctor: {
						body: false,
						edited: false,
					},
					childrenDoctor: {
						body: false,
						edited: false,
					},
					hide: {
						body: false,
						edited: false,
					},
					filename: {
						body: "",
						edited: false,
					},
				},
			},
			specialists: [
				{
					id: 1,
					name: "Иванов Иван Иванович",
					specialization: "Ортопед",
					hide: false,
					delete: false,
				},
			],
		};
	},
};
</script>

<style scoped></style>
