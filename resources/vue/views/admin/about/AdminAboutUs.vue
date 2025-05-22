<template>
	<!-- Модальное окно: Информационный блок -->
	<Modal ref="modal" :settings="modal">
		<template
			#title
			v-if="modal.type == 'edit' && !currentInfoBlock.data.delete.value && !modal.style.create"
		>
			<Icon
				:name="'arrow'"
				:fill="'var(--icon-multi-fill)'"
				:hover="'var(--icon-nulti-fill-hover)'"
				:width="'16px'"
				:height="'16px'"
				:rotate="-90"
				:cursor="'pointer'"
				@click="changeInfoBlockOrder('down')"
			/>
			#{{ currentInfoBlock.data.order.value }}
			<Icon
				:name="'arrow'"
				:fill="'var(--icon-multi-fill)'"
				:hover="'var(--icon-nulti-fill-hover)'"
				:width="'16px'"
				:height="'16px'"
				:rotate="90"
				:cursor="'pointer'"
				@click="changeInfoBlockOrder('up')"
			/>
		</template>

		<template #title v-else>
			{{ modal.title }}
		</template>

		<template #body>
			<div class="modal-images">
				<div class="item" v-if="currentInfoBlock.data.imageOne.value">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${currentInfoBlock.data.pathOne.value})`,
						}"
					></div>
					<div class="buttons" v-if="!currentInfoBlock.data.delete.value">
						<div class="icon edit" @click="openModalImageEdite('imageOne')">
							<Icon
								:name="'edit'"
								:fill="'var(--icon-edit-fill)'"
								:width="'24px'"
								:height="'24px'"
							/>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageOne')">
							<Icon
								:name="'delete'"
								:fill="'var(--delete-secondary-color)'"
								:width="'24px'"
								:height="'22px'"
							/>
						</div>
					</div>
				</div>
				<div class="item default" v-else>
					<div
						class="img"
						:style="{
							backgroundImage: `url(/storage/default/stones-none-default.png)`,
						}"
					></div>
					<div class="buttons add" v-if="!currentInfoBlock.data.delete.value">
						<div class="icon create" @click="openModalImageCreate('imageOne')">
							<Icon
								:name="'add'"
								:fill="'var(--icon-create-fill)'"
								:width="'19px'"
								:height="'19px'"
							/>
						</div>
					</div>
				</div>
				<div class="item" v-if="currentInfoBlock.data.imageTwo.value">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${currentInfoBlock.data.pathTwo.value})`,
						}"
					></div>
					<div class="buttons" v-if="!currentInfoBlock.data.delete.value">
						<div class="icon edit" @click="openModalImageEdite('imageTwo')">
							<Icon
								:name="'edit'"
								:fill="'var(--icon-edit-fill)'"
								:width="'24px'"
								:height="'24px'"
							/>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageTwo')">
							<Icon
								:name="'delete'"
								:fill="'var(--delete-secondary-color)'"
								:width="'24px'"
								:height="'22px'"
							/>
						</div>
					</div>
				</div>
				<div class="item default" v-else>
					<div
						class="img"
						:style="{
							backgroundImage: `url(/storage/default/stones-none-default.png)`,
						}"
					></div>
					<div class="buttons add" v-if="!currentInfoBlock.data.delete.value">
						<div class="icon create" @click="openModalImageCreate('imageTwo')">
							<Icon
								:name="'add'"
								:fill="'var(--icon-create-fill)'"
								:width="'19px'"
								:height="'19px'"
							/>
						</div>
					</div>
				</div>
				<div class="item" v-if="currentInfoBlock.data.imageThree.value">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${currentInfoBlock.data.pathThree.value})`,
						}"
					></div>
					<div class="buttons" v-if="!currentInfoBlock.data.delete.value">
						<div class="icon edit" @click="openModalImageEdite('imageThree')">
							<Icon
								:name="'edit'"
								:fill="'var(--icon-edit-fill)'"
								:width="'24px'"
								:height="'24px'"
							/>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageThree')">
							<Icon
								:name="'delete'"
								:fill="'var(--delete-secondary-color)'"
								:width="'24px'"
								:height="'22px'"
							/>
						</div>
					</div>
				</div>
				<div class="item default" v-else>
					<div
						class="img"
						:style="{
							backgroundImage: `url(/storage/default/stones-none-default.png)`,
						}"
					></div>
					<div class="buttons add" v-if="!currentInfoBlock.data.delete.value">
						<div class="icon create" @click="openModalImageCreate('imageThree')">
							<Icon
								:name="'add'"
								:fill="'var(--icon-create-fill)'"
								:width="'19px'"
								:height="'19px'"
							/>
						</div>
					</div>
				</div>
			</div>
			<ContainerInput>
				<Tiptap
					ref="tiptapTitle"
					v-model="currentInfoBlock.data.title.value"
					:editable="true"
					:limit="500"
					:minHeight="100"
					:options="['format', 'align']"
					:placeholder="'Заголовок'"
				/>

				<Tiptap
					ref="tiptapDescription"
					v-model="currentInfoBlock.data.description.value"
					:editable="true"
					:limit="10_000"
					:minHeight="200"
					:options="['format', 'align', 'list', 'link']"
					:placeholder="'Заголовок'"
				/>
			</ContainerInput>
		</template>

		<template #footer>
			<template v-if="modal.values.look == 'create'">
				<ButtonDefault @click="addInfoBlock">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</ButtonDefault>
			</template>

			<template v-if="modal.values.look == 'default'">
				<button-remove
					@click="deleteInfoBlock"
					v-if="!currentInfoBlock.data.delete.value && !currentInfoBlock.data.create.value"
				>
					<Icon :name="'delete'" :fill="'white'" :width="'24px'" :height="'22px'" />
					Удалить
				</button-remove>

				<ButtonDefault @click="updateInfoBlock" v-if="!currentInfoBlock.data.delete.value">
					<Icon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</ButtonDefault>

				<ButtonDefault @click="deleteInfoBlock" v-if="currentInfoBlock.data.delete.value">
					<Icon :name="'restore'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Вернуть
				</ButtonDefault>
			</template>
		</template>
	</Modal>

	<!-- Модальное окно: Добавить картинку -->
	<Modal ref="modalImage" :settings="modalImage">
		<template #title>
			{{ modalImage.values.title }}
		</template>

		<template #body>
			<container-input-once>
				<template #input>
					<input
						type="file"
						autocomplete="off"
						ref="fileUpload"
						:class="{ error: currentImage.errors.file.status }"
						@input="currentImage.data.file.edited = true"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentImage.errors.file.status">
						{{ currentImage.errors.file.message }}
					</span>
				</template>
			</container-input-once>
		</template>

		<template #footer>
			<template v-if="modalImage.values.look == 'create'">
				<button-default @click="updateImage" :disabled="disabled.image.add">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
			</template>

			<template v-if="modalImage.values.look == 'default'">
				<button-default @click="updateImage" :disabled="disabled.image.update">
					<Icon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</button-default>
			</template>
		</template>
	</Modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|               ИНФОРМАЦИОННЫЕ БЛОКИ                |-->
	<!--|___________________________________________________|-->
	<info-bar>
		<template v-slot:title>О нас</template>
		<template v-slot:addreas>about</template>
	</info-bar>

	<block-once :minHeight="300">
		<template #title>ИНФОРМАЦИОННЫЕ БЛОКИ</template>

		<template #options>
			<button-default
				@click.prevent="saveInfoBlocks"
				:disabled="disabled.about.save"
				:look="'white'"
			>
				<Icon :name="'save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</button-default>
		</template>

		<template #body>
			<template v-if="loading.sections.infoBlocks">
				<AdminAboutUsList
					:infoBlocks="infoBlocks"
					@touchEditBlock="openModalEdite"
					v-if="infoBlocks.length > 0"
				/>

				<!-- Элемент пустой страницы -->
				<empty :minHeight="300" v-if="infoBlocks.length == 0" />
			</template>

			<LoaderChild
				:isLoading="loading.loader.infoBlocks"
				:minHeight="300"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			></LoaderChild>
		</template>

		<template #buttons>
			<button-default @click="openModalСreate">
				<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
				Добавить
			</button-default>
		</template>
	</block-once>
</template>

<script>
import Modal from "../../../components/modules/modal/Modal.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import Tiptap from "../../../components/modules/Tiptap.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTwo from "../../../components/ui/admin/blocks/BlockTwo.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import Icon from "../../../components/modules/icon/Icon.vue";

import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Empty from "../../../components/modules/Empty.vue";

import AdminAboutUsList from "./AdminAboutUsList.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerTextareaOnce from "../../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

import axios from "axios";
import shared from "../../../services/shared";
import files from "../../../services/files";
import sorted from "../../../services/sorted";
import validate from "../../../services/validate";

export default {
	components: {
		Modal,

		InfoBar,
		Tiptap,
		LoaderChild,

		BlockOnce,
		BlockTwo,
		BlockButtons,

		ButtonDefault,
		ButtonRemove,
		ButtonClaim,

		Icon,

		Empty,
		AdminAboutUsList,
		ContainerInput,
		ContainerInputOnce,
		ContainerTextareaOnce,

		axios,
	},
	data() {
		return {
			/* Кнопки */
			disabled: {
				about: {
					update: false,
					delete: false,
					create: false,
					add: false,
					save: false,
				},
				image: {
					update: false,
					add: false,
				},
			},

			/* Загрузчик */
			loading: {
				loader: {
					title: true,
					infoBlocks: true,
				},
				sections: {
					title: false,
					infoBlocks: false,
				},
			},

			/* Модальное окно */
			modal: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalImage: {
				thin: true,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			currentInfoBlock: {
				errors: {
					id: {
						message: "",
						status: false,
					},
					order: {
						message: "",
						status: false,
					},
					title: {
						message: "",
						status: false,
					},
					description: {
						message: "",
						status: false,
					},
					imageOne: {
						message: "",
						status: false,
					},
					imageTwo: {
						message: "",
						status: false,
					},
					imageThree: {
						message: "",
						status: false,
					},
				},
				data: {
					id: {
						value: "",
						edited: false,
					},
					order: {
						value: "",
						edited: false,
					},
					title: {
						value: "",
						edited: false,
					},
					description: {
						value: "",
						edited: false,
					},
					imageOne: {
						value: "",
						edited: false,
					},
					imageTwo: {
						value: "",
						edited: false,
					},
					imageThree: {
						value: "",
						edited: false,
					},
					pathOne: {
						value: "",
						edited: false,
					},
					pathTwo: {
						value: "",
						edited: false,
					},
					pathThree: {
						value: "",
						edited: false,
					},
					create: {
						value: false,
						edited: false,
					},
					delete: {
						value: false,
						edited: false,
					},
				},
			},

			currentImage: {
				errors: {
					file: {
						message: "",
						status: false,
					},
				},
				data: {
					file: {
						value: "",
						edited: false,
					},
				},
			},

			/* Список */
			infoBlocks: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия загрузчика */
		loaderChildAfterLeave() {
			this.loading.sections.title = true;
			this.loading.sections.infoBlocks = true;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openModalСreate() {
			shared.clearObjectFull(this.currentInfoBlock);

			this.openModal("modal", "БЛОК ИНФОРМАЦИИ", "create");
		},

		/* Открытие модального окна для редактирования */
		openModalEdite(value) {
			shared.clearObjectFull(this.currentInfoBlock);
			shared.setData(value, this.currentInfoBlock);

			this.openModal("modal", "БЛОК ИНФОРМАЦИИ", "default");
		},

		/* Открытие модального окна для загрузки */
		openModalImageCreate(name) {
			this.currentImage.data.file.value = name;
			this.currentImage.data.file.edited = false;
			this.currentImage.errors.file.status = false;

			this.$refs.fileUpload.value = "";

			this.openModal("modalImage", "ЗАГРУЗКА ИЗОБРАЖЕНИЯ", "default");
		},

		/* Открытие модального окна для загрузки */
		openModalImageEdite(name) {
			this.currentImage.data.file.value = name;
			this.currentImage.data.file.edited = false;
			this.currentImage.errors.file.status = false;

			this.$refs.fileUpload.value = "";

			this.openModal("modalImage", "ЗАГРУЗКА ИЗОБРАЖЕНИЯ", "default");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |              ИНФОРМАЦИОННЫЕ БЛОКИ                 |*/
		/* |___________________________________________________|*/
		/* Обновление картинки */
		updateImage() {
			if (
				validate.checkInputsAll(this.currentImage, [
					{
						key: "file",
						type: "file",
						value: this.$refs.fileUpload,
						formats: ["jpg", "jpeg", "png", "webp"],
					},
				])
			)
				return;

			/* Загрузка файла */
			let formData = new FormData();
			formData.append("file", this.$refs.fileUpload.files[0]);
			formData.append("type", "abouts");
			formData.append("formats", ["png", "jpg", "jpeg", "webp"]);

			if (this.modalImage.values.look == "create") {
				this.disabled.image.add = true;
			} else if (this.modalImage.values.look == "default") {
				this.disabled.image.update = true;
			}

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `upload-file`,
				headers: {
					"Content-Type": "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						try {
							switch (this.currentImage.data.file.value) {
								case "imageOne":
									this.currentInfoBlock.data.pathOne.value = response.data.data;
									this.currentInfoBlock.data.imageOne.value = files.basename(
										response.data.data
									);
									break;
								case "imageTwo":
									this.currentInfoBlock.data.pathTwo.value = response.data.data;
									this.currentInfoBlock.data.imageTwo.value = files.basename(
										response.data.data
									);
									break;
								case "imageThree":
									this.currentInfoBlock.data.pathThree.value = response.data.data;
									this.currentInfoBlock.data.imageThree.value = files.basename(
										response.data.data
									);
									break;
							}

							this.$refs.modalImage.close();
						} catch (error) {
							this.$store.commit("addDebugger", {
								title: "Ошибка.",
								body: error,
								type: "error",
							});
						}
					} else {
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: response.data.message,
							type: "error",
						});
					}
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: response.data.message,
						type: "error",
					});
				})
				.finally(() => {
					if (this.modalImage.values.look == "create") {
						this.disabled.image.add = false;
					}

					if (this.modalImage.values.look == "default") {
						this.disabled.image.update = false;
					}
				});
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |              ИНФОРМАЦИОННЫЕ БЛОКИ                 |*/
		/* |___________________________________________________|*/
		/* Удаление */
		deleteInfoBlock() {
			try {
				let block = this.infoBlocks.find(
					(item) => item.id == this.currentInfoBlock.data.id.value
				);

				block.delete = !block.delete;

				this.$refs.modal.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Добавление */
		addInfoBlock() {
			try {
				this.infoBlocks.push({
					id: shared.getMaxId(this.infoBlocks) + 1,
					order: shared.getMaxOrder(this.infoBlocks) + 1,
					title: this.currentInfoBlock.data.title.value,
					description: this.currentInfoBlock.data.description.value,
					imageOne: this.currentInfoBlock.data.imageOne.value,
					imageTwo: this.currentInfoBlock.data.imageTwo.value,
					imageThree: this.currentInfoBlock.data.imageThree.value,
					pathOne: this.currentInfoBlock.data.pathOne.value,
					pathTwo: this.currentInfoBlock.data.pathTwo.value,
					pathThree: this.currentInfoBlock.data.pathThree.value,
					create: true,
					delete: false,
				});

				this.$refs.modal.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Обновление */
		updateInfoBlock() {
			try {
				let block = this.infoBlocks.find(
					(item) => item.id == this.currentInfoBlock.data.id.value
				);

				for (let key in this.currentInfoBlock.data) {
					block[key] = this.currentInfoBlock.data[key].value;
				}

				this.$refs.modal.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Удаление */
		removeInfoBlockImage(name) {
			this.currentInfoBlock.data[name].value = "";
		},

		/* Изменение порядка */
		changeInfoBlockOrder(type) {
			try {
				shared.changeOrder(this.infoBlocks, this.currentInfoBlock, type);
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},

		/* Сохранение */
		saveInfoBlocks() {
			this.disabled.about.save = true;

			let formData = new FormData();
			formData.append("abouts", JSON.stringify(this.infoBlocks));

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-abouts-changes`,
				headers: {
					ContentType: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						try {
							shared.updateId(this.infoBlocks, response.data.data);
							shared.clearDeletes(this.infoBlocks);
							shared.clearFlags(this.infoBlocks);
							shared.updateOrders(this.infoBlocks);

							this.$store.commit("addDebugger", {
								title: "Успешно!",
								body: response.data.message,
								type: "completed",
							});
						} catch (error) {
							this.$store.commit("addDebugger", {
								title: "Ошибка.",
								body: response.data.message,
								type: "error",
							});
						}
					} else {
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: response.data.message,
							type: "error",
						});
					}
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.about.save = false;
				});
		},
	},
	mounted() {
		axios({
			method: "get",
			headers: {
				Accept: "application/json",
			},
			url: `${this.$store.getters.urlApi}` + `get-abouts-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.infoBlocks = response.data.data;

					this.infoBlocks.forEach((item) => {
						item.create = false;
						item.delete = false;
					});

					sorted.sortByOrder("up", this.infoBlocks);
				} else {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: response.data.message,
						type: "error",
					});
				}
			})
			.catch((error) => {
				console.log(error);
			})
			.finally(() => {
				this.loading.loader.infoBlocks = false;
			});
	},
};
</script>

<style scoped>
.modal-images {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 20px;

	border: var(--default-border);
	border-radius: var(--default-border-radius);
	padding: var(--default-padding);

	background-color: var(--item-background-color);
}

.modal-images > .item {
	position: relative;
	flex: 1 0 200px;
	gap: 10px;

	border-radius: calc(var(--default-border-radius) / 1.5);
	min-width: 100px;
	min-height: 300px;

	background-color: white;
}

.modal-images > .item.default {
	box-sizing: border-box;
	width: 100px;
	height: 100px;
}

.modal-images > .item > .img {
	flex: 1 0 300px;
	border-radius: calc(var(--default-border-radius) / 1.5);
	min-width: 100px;
	min-height: 300px;
	background-position: center center;
	background-repeat: no-repeat;
	background-size: cover;
}

.modal-images > .item > .buttons {
	position: absolute;
	display: flex;
	gap: 5px;
	top: 10px;
	right: 10px;
}

.modal-images > .item > .buttons.add {
	top: 10px;
	right: 10px;
}

.modal-images > .item > .buttons > .icon {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;
	padding: 10px;
	border-radius: 100px;

	width: 25px;
	height: 25px;
}

.modal-images > .item > .buttons > .icon.delete {
	background-color: var(--icon-delete-background-color);
}

.modal-images > .item > .buttons > .icon.delete:hover {
	background-color: var(--icon-delete-background-color-hover);
}

.modal-images > .item > .buttons > .icon.edit {
	background-color: var(--icon-edit-background-color);
}

.modal-images > .item > .buttons > .icon.edit:hover {
	background-color: var(--icon-edit-background-color-hover);
}

.modal-images > .item > .buttons > .icon.create {
	background-color: var(--icon-create-background-color);
}

.modal-images > .item > .buttons > .icon.create:hover {
	background-color: var(--icon-create-background-color-hover);
}

.textarea-show {
	animation: show-bottom-to-top-15 0.5s ease-in-out;
}
</style>
