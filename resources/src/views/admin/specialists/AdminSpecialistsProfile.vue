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
			<div class="profile-image-loader" v-if="loading.loader">Идёт загрузка...</div>
			<img
				:src="`/storage/slides/lesnikova.png`"
				class="profile-image"
				alt=""
				v-if="!loading.loader"
			/>
			<div class="profile-info">
				<container-input-two :fieldset="true">
					<template #legend>АВАТАР И ССЫЛКА</template>
					<template #title-one>ФОТО ВРАЧА</template>
					<template #input-one>
						<input
							:class="{ 'input-load': loading.loader }"
							class="profile-file"
							type="file"
							autocomplete="off"
						/>
					</template>
					<template #title-two>ССЫЛКА НА ПРОДОКТОРОВ</template>
					<template #input-two>
						<input
							:class="{ 'input-load': loading.loader }"
							type="text"
							placeholder="Введите ссылку"
							autocomplete="off"
							v-model="spesialist.profile.link.body"
						/>
					</template>
				</container-input-two>
				<container-input-three :fieldset="true">
					<template #legend>Ф.И.О.</template>
					<template #title-one>ФАМИЛИЯ</template>
					<template #input-one>
						<input
							:class="{ 'input-load': loading.loader }"
							type="text"
							placeholder="Введите фамилию"
							autocomplete="off"
							v-model="spesialist.profile.family.body"
						/>
					</template>
					<template #title-two>ИМЯ</template>
					<template #input-two>
						<input
							:class="{ 'input-load': loading.loader }"
							type="text"
							placeholder="Введите имя"
							autocomplete="off"
							v-model="spesialist.profile.name.body"
						/>
					</template>
					<template #title-three>ОТЧЕСТВО</template>
					<template #input-three>
						<input
							:class="{ 'input-load': loading.loader }"
							type="text"
							placeholder="Введите отчество"
							autocomplete="off"
							v-model="spesialist.profile.surname.body"
						/>
					</template>
				</container-input-three>
			</div>
		</div>
		<!-- Первая работа -->
		<container-input>
			<container-input-two :fieldset="true">
				<template #legend>ПЕРВАЯ РАБОТА</template>
				<template #title-one>НАЧАЛО ПЕРВОЙ РАБОТЫ</template>
				<template #input-one>
					<input
						type="date"
						:class="{ 'input-load': loading.loader }"
						v-model="spesialist.profile.startWorkAge.body"
					/>
				</template>
				<template #title-two>ГОРОД ПЕРВОЙ РАБОТЫ</template>
				<template #input-two>
					<input
						:class="{ 'input-load': loading.loader }"
						type="text"
						placeholder="Введите название города"
						autocomplete="off"
						v-model="spesialist.profile.startWorkCity.body"
					/>
				</template>
			</container-input-two>
			<container-input-two :fieldset="true">
				<template #legend>ПРИЁМ ВРАЧА</template>
				<template #title-one>У ВЗРОСЛЫХ</template>
				<template #input-one>
					<select
						v-model="spesialist.profile.adultDoctor.body"
						:class="{ 'input-load': loading.loader }"
					>
						<option value="0">Нет</option>
						<option value="1">Да</option>
					</select>
				</template>
				<template #title-two>У ДЕТЕЙ</template>
				<template #input-two>
					<select
						v-model="spesialist.profile.childrenDoctor.body"
						autocomplete="off"
						:class="{ 'input-load': loading.loader }"
					>
						<option value="0">Нет</option>
						<option value="1">Да</option>
					</select>
				</template>
			</container-input-two>
		</container-input>
	</block-once>
	<!-- Сертификаты -->
	<block-once>
		<block-title>
			<template #title> Сертификаты </template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<div class="profile-list">
			<!-- Если сертификаты не выбраны -->
			<div class="item-empty" v-if="spesialist.certificates.length == 0 && !loading.loader">
				<div class="item-title">Пока тут ничего нет...</div>
			</div>
			<!-- Пока идёт загрузка -->
			<div class="item" v-if="loading.loader" :class="{ 'item-load': loading.loader }">
				<div class="item-title"></div>
				<div class="item-close">
					<icon-close :width="26" :height="26" />
				</div>
			</div>
			<!-- Если специализации выбраны -->
			<div class="item" v-for="certificate in spesialist.certificates" :key="certificate.id">
				<div class="item-title">{{ certificate.name }}</div>
				<div class="item-close" @click="removeArrValue('certificates', certificate)">
					<icon-close :width="26" :height="26" />
				</div>
			</div>
		</div>

		<block-buttons>
			<button-default> Добавить </button-default>
		</block-buttons>
	</block-once>

	<!-- Специализации и клиники -->
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
			<div class="profile-list">
				<!-- Если специализации не выбраны -->
				<div
					class="item-empty"
					v-if="spesialist.specializations.length == 0 && !loading.loader"
				>
					<div class="item-title">Пока тут ничего нет...</div>
				</div>
				<!-- Пока идёт загрузка -->
				<div class="item" v-if="loading.loader" :class="{ 'item-load': loading.loader }">
					<div class="item-title"></div>
					<div class="item-close">
						<icon-close :width="26" :height="26" />
					</div>
				</div>
				<!-- Если специализации выбраны -->
				<div
					class="item"
					v-for="specialization in spesialist.specializations"
					:key="specialization.id"
				>
					<div class="item-title">{{ specialization.name }}</div>
					<div class="item-close" @click="removeArrValue('specializations', specialization)">
						<icon-close :width="26" :height="26" />
					</div>
				</div>
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
			<div class="profile-list">
				<!-- Если клиники не выбраны -->
				<div class="item-empty" v-if="spesialist.clinics.length == 0 && !loading.loader">
					<div class="item-title">Пока тут ничего нет...</div>
				</div>
				<!-- Пока идёт загрузка -->
				<div class="item" v-if="loading.loader" :class="{ 'item-load': loading.loader }">
					<div class="item-title"></div>
					<div class="item-close">
						<icon-close :width="26" :height="26" />
					</div>
				</div>
				<!-- Если клиники выбраны -->
				<div class="item" v-for="clinic in spesialist.clinics" :key="clinic.id">
					<div class="item-title">{{ clinic.name }}</div>
					<div class="item-close" @click="removeArrValue('clinics', clinic)">
						<icon-close :width="26" :height="26" />
					</div>
				</div>
			</div>

			<block-buttons>
				<button-default> Добавить </button-default>
			</block-buttons>
		</template>
	</block-two>
	<!-- Специализации и клиники -->
	<block-two>
		<template #title-one>
			<block-title>
				<template #title> Образование </template>
				<template #buttons>
					<icon-save :width="28" :height="28" />
				</template>
			</block-title>
		</template>
		<template #body-one>
			<div class="profile-list">
				<!-- Если образования не выбраны -->
				<div class="item-empty" v-if="spesialist.educations.length == 0 && !loading.loader">
					<div class="item-title">Пока тут ничего нет...</div>
				</div>
				<!-- Пока идёт загрузка -->
				<div class="item" v-if="loading.loader" :class="{ 'item-load': loading.loader }">
					<div class="item-title"></div>
					<div class="item-close">
						<icon-close :width="26" :height="26" />
					</div>
				</div>
				<!-- Если образования выбраны -->
				<div class="item" v-for="education in spesialist.educations" :key="education.id">
					<div class="item-title">{{ education.name }}</div>
					<div class="item-close" @click="removeArrValue('educations', education)">
						<icon-close :width="26" :height="26" />
					</div>
				</div>
			</div>

			<block-buttons>
				<button-default> Добавить </button-default>
			</block-buttons>
		</template>
		<template #title-two>
			<block-title>
				<template #title> Места работы </template>
				<template #buttons>
					<icon-save :width="28" :height="28" />
				</template>
			</block-title>
		</template>
		<template #body-two>
			<div class="profile-list">
				<!-- Если образования не выбраны -->
				<div class="item-empty" v-if="spesialist.works.length == 0 && !loading.loader">
					<div class="item-title">Пока тут ничего нет...</div>
				</div>
				<!-- Пока идёт загрузка -->
				<div class="item" v-if="loading.loader" :class="{ 'item-load': loading.loader }">
					<div class="item-title"></div>
					<div class="item-close">
						<icon-close :width="26" :height="26" />
					</div>
				</div>
				<!-- Если образования выбраны -->
				<div class="item" v-for="work in spesialist.works" :key="work.id">
					<div class="item-title">{{ work.name }}</div>
					<div class="item-close" @click="removeArrValue('works', work)">
						<icon-close :width="26" :height="26" />
					</div>
				</div>
			</div>

			<block-buttons>
				<button-default> Добавить </button-default>
			</block-buttons>
		</template>
	</block-two>
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

import TableButtonDefault from "../../../components/ui/admin/TableButtonDefault.vue";
import TableButtonRemove from "../../../components/ui/admin/TableButtonRemove.vue";

import IconClose from "../../../components/icons/IconClose.vue";

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
		IconClose,
		SpanError,
		ContainerInput,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputThree,
		TableButtonDefault,
		TableButtonRemove,
		ButtonDefault,
		ButtonRemove,
		IconSave,
		axios,
		RouterView,
		RouterLink,
	},
	data() {
		return {
			loading: {
				loader: true,
			},
			spesialist: {
				profile: {
					file: "",
					id: {
						body: "",
						edited: false,
					},
					link: {
						body: "",
						edited: false,
					},
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
				certificates: [],
				specializations: [],
				clinics: [],
				educations: [],
				works: [],
			},
		};
	},
	methods: {
		// Метод удаления значения из массива
		removeArrValue(array, value) {
			this.spesialist[array] = this.spesialist[array].filter((item) => {
				if (item.id !== value.id) {
					return item;
				}
			});
		},
	},
	mounted() {
		axios({
			method: "post",
			headers: {
				Accept: "application/json",
			},
			url: `${this.$store.state.axios.urlApi}` + `get-specialist-all`,
			data: {
				id: this.$route.params.id,
			},
		})
			.then((response) => {
				for (let key in response.data) {
					this.spesialist.profile[key].body = response.data[key];
				}
			})
			.catch((error) => {
				console.log(error);
			})
			.finally(() => {
				this.loading.loader = false;
			});
	},
};
</script>

<style scoped>
.container-profile {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 20px;
}

.profile-image-loader {
	display: flex;
	justify-content: center;
	align-items: center;
	font-size: 20px;
	color: #bcbcbc;
}

.profile-image {
	align-self: center;
	justify-self: center;
	flex: 1 0 340px;
	max-width: 450px;
	border-radius: 10px;
}

.profile-info {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	gap: 20px;
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

.profile-list {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.profile-list > .item {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 10px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px;
	transition: all 0.2s;
}

.profile-list > .item-empty {
	display: flex;
	justify-content: center;
	align-items: center;
	height: 100%;
	color: #bcbcbc;
}
</style>
