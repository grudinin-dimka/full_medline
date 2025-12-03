<template>
	<VueInput :error="error">
		<template #label v-if="$slots.label">
			<slot name="label"></slot>
		</template>

		<template #input>
			<div class="vue-input__wrapper">
				<label class="vue-input__file-button">
					<input
						name="file"
						type="file"
						class="vue-input vue-input__file"
						ref="image"
						@change="handleFileChange"
					/>

					<span class="vue-input__file-icon-wrapper" ref="imageWrapper">
						<span class="vue-input__file-text" ref="imageText">
							<svg
								class="vue-input__file-icon"
								width="20"
								height="26"
								viewBox="0 0 16 20"
								fill="none"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M7 17H9V12.825L10.6 14.425L12 13L8 9L4 13L5.425 14.4L7 12.825V17ZM2 20C1.45 20 0.979167 19.8042 0.5875 19.4125C0.195833 19.0208 0 18.55 0 18V2C0 1.45 0.195833 0.979167 0.5875 0.5875C0.979167 0.195833 1.45 0 2 0H10L16 6V18C16 18.55 15.8042 19.0208 15.4125 19.4125C15.0208 19.8042 14.55 20 14 20H2ZM9 7V2H2V18H14V7H9Z"
								/>
							</svg>

							<template v-if="hasFile">
								{{ fileName ?? "Файл загружен" }}
							</template>
							<template v-else>
								{{ placeholder ? placeholder : "Файл не загружен" }}
							</template>
						</span>
					</span>
				</label>
				<div class="input__file-button-error" v-if="false">error</div>
			</div>
		</template>

		<template #error v-if="error">
			<slot name="error"></slot>
		</template>
	</VueInput>
</template>

<script>
import VueInput from "./VueInput.vue";

export default {
	name: "VueFile",
	components: { VueInput },
	props: {
		modelValue: {
			type: [String, Number, Boolean, null, Object],
			default: "",
			required: true,
		},

		placeholder: {
			type: String,
			default: "Введите значение",
		},
		error: {
			type: Boolean,
			default: false,
		},
	},
	data() {
		return {
			fileName: "",
			hasFile: false,
		};
	},
	methods: {
		/* Получение массива файлов */
		files() {
			return this.$refs?.image.files ?? undefined;
		},

		/* Очистка файлов в поле ввода */
		clear() {
			this.hasFile = false;
			this.fileName = "";

			this.$refs.image.value = "";
		},

		/* Отслеживание изменений файлов */
		handleFileChange(event) {
			const files = event.target.files;

			if (files && files.length > 0) {
				this.hasFile = true;

				this.fileName = event.target.files[0].name;
			}
		},
	},
};
</script>

<style>
/* Файл */
.vue-input__wrapper {
	height: 100%;
	width: 100%;
	position: relative;
	text-align: center;

	display: flex;
	flex-direction: column;
}

.vue-input__file {
	opacity: 0;
	visibility: hidden;
	position: absolute;
	width: 0px;
}

.vue-input__file-icon-wrapper {
	box-sizing: border-box;
	margin-right: 15px;
	display: -webkit-box;
	display: -ms-flexbox;
	display: flex;
	flex-wrap: wrap;

	word-wrap: break-word;
	word-break: break-all;

	width: auto;
	height: 60px;
	padding: 0px 20px;

	-webkit-box-align: center;
	-ms-flex-align: center;
	align-items: center;
	-webkit-box-pack: center;
	-ms-flex-pack: center;
	justify-content: center;
}

.vue-input__file-text {
	display: grid;
	justify-content: center;
	align-items: center;
	gap: 10px;

	grid-template-columns: auto auto;
}

.vue-input__file-icon {
	fill: var(--input-file-icon-color);

	transition: all 0.2s ease;
}

.vue-input__file-button {
	box-sizing: border-box;
	user-select: none;
	cursor: pointer;
	display: -webkit-box;
	display: -ms-flexbox;
	display: flex;
	-webkit-box-align: center;
	-ms-flex-align: center;
	align-items: center;
	-webkit-box-pack: start;
	-ms-flex-pack: start;

	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 2);
	margin: 0 auto;

	width: 100%;

	font-size: 1.25rem;
	white-space: nowrap;
	overflow: hidden;

	color: var(--input-color);
	background-color: var(--input-background-color);

	transition: all 0.2s;
}

.vue-input__file-button:is(:hover, .active, :focus) {
	border: var(--input-border-focus);
	color: var(--primary-color);
	background-color: var(--input-background-color);
}

.vue-input__file-button:is(:hover, .active) .vue-input__file-icon {
	fill: var(--primary-color);
}

.vue-input__file-button.error {
	border: var(--input-error-border);
	border-radius: 10px 10px 0px 0px;

	color: var(--input-error-color);
	background-color: var(--input-error-background-color);
}
</style>
