<template>
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists/{{ $route.params.id }}</template>
	</info-bar>

	<!-- Основные данные врача -->
	<block-once>
		<block-title>
			<template #title>Профиль</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>
		<div class="container-profile">
			<div
				class="profile-image"
				:style="{
					backgroundImage: `url(/storage/slides/lesnikova.png)`,
				}"
			></div>
			<input class="profile-file" type="file" placeholder="введите фамилию" autocomplete="off" />
		</div>
		<container-input>
			<container-input-three :fieldset="true">
				<template #legend>Ф.И.О.</template>
				<template #title-one>Фамилия</template>
				<template #input-one>
					<input
						type="text"
						v-model="spesialist.profile.family.body"
						placeholder="введите фамилию"
						autocomplete="off"
					/>
				</template>
				<template #title-two>Имя</template>
				<template #input-two>
					<input
						type="text"
						v-model="spesialist.profile.name.body"
						placeholder="введите имя"
						autocomplete="off"
					/>
				</template>
				<template #title-three>Отчество</template>
				<template #input-three>
					<input
						type="text"
						v-model="spesialist.profile.surname.body"
						placeholder="введите отчество"
						autocomplete="off"
					/>
				</template>
			</container-input-three>
			<container-input-two :fieldset="true">
				<template #legend>Первая работа</template>
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
						autocomplete="off"
					/>
				</template>
			</container-input-two>
			<container-input-two :fieldset="true">
				<template #legend>Приём врача</template>
				<template #title-one>У взрослых</template>
				<template #input-one>
					<select v-model="spesialist.profile.adultDoctor.body">
						<option value="false">Нет</option>
						<option value="true">Да</option>
					</select>
				</template>
				<template #title-two>У детей</template>
				<template #input-two>
					<select v-model="spesialist.profile.childrenDoctor.body" autocomplete="off">
						<option value="false">Нет</option>
						<option value="true">Да</option>
					</select>
				</template>
			</container-input-two>
		</container-input>

		<block-buttons v-if="$route.params.id === 'new'">
			<button-default> Добавить </button-default>
		</block-buttons>
	</block-once>

	<block-two>
		<template #title-one>
			<block-title>
				<template #title> Специализации </template>
				<template #buttons>
					<icon-save :width="28" :height="28" />
				</template>
			</block-title>
		</template>
		<template #body-one>
			<div class="profile-specializations">
				<div class="item">г. Шадринск, ул. Комсомольская, 16</div>
				<div class="item">г. Шадринск, ул. Карла Либкнехта, 10</div>
			</div>

			<block-buttons>
				<button-default> Добавить </button-default>
			</block-buttons>
		</template>
		<template #title-two>
			<block-title>
				<template #title> Клиники </template>
				<template #buttons>
					<icon-save :width="28" :height="28" />
				</template>
			</block-title>
		</template>
		<template #body-two>
			<div class="profile-clinics">
				<div class="item">г. Шадринск, ул. Комсомольская, 16</div>
				<div class="item">г. Шадринск, ул. Карла Либкнехта, 10</div>
				<div class="item">г. Шадринск, ул. Октябрьская, 3</div>
				<div class="item">р. п. Каргаполье, ул. Мира, 5г</div>
			</div>

			<block-buttons>
				<button-default> Добавить </button-default>
			</block-buttons>
		</template>
	</block-two>

	<block-once>
		<block-title>
			<template #title>Образование</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<block-buttons>
			<button-default> Добавить </button-default>
		</block-buttons>
	</block-once>

	<block-once>
		<block-title>
			<template #title>Место работы</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<block-buttons>
			<button-default> Добавить </button-default>
		</block-buttons>
	</block-once>

	<block-once>
		<block-title>
			<template #title>Сертификаты</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<block-buttons v-if="$route.params.id === 'new'">
			<button-default> Добавить </button-default>
		</block-buttons>
	</block-once>
</template>

<script>
import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/BlockOnce.vue";
import BlockTwo from "../../../components/ui/admin/BlockTwo.vue";
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
		BlockOnce,
		BlockTwo,
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

<style scoped>
.container-profile {
	display: flex;
	gap: 20px;
	justify-content: space-evenly;
}

.profile-image {
	height: 400px;
	width: 340px;
	background-size: cover;
	background-position: center;
	background-repeat: no-repeat;
	border-radius: 10px;
}

.profile-file {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	font-size: 20px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

.profile-file::file-selector-button {
	flex-grow: 1;
	cursor: pointer;
	background-color: var(--button-default-color);
	border: 0px;
	border-radius: 5px;
	color: white;
	padding: 5px;
}

.profile-file:focus {
	border: 2px solid var(--input-border-color-active);
}

.profile-clinics,
.profile-specializations {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.profile-clinics > .item,
.profile-specializations > .item {
	cursor: pointer;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px;
	transition: all 0.2s;
}

.profile-clinics > .item:hover,
.profile-specializations > .item:hover {
	border: 2px solid var(--input-border-color-active);
}
</style>
