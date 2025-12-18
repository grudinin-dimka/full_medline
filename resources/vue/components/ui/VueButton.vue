<template>
	<button
		class="button-default"
		ref="button"
		:type="type"
		:class="{ [look]: look, wide: wide, [figure]: figure }"
		:disabled="disabled"
	>
		<span class="loader" v-if="disabled" :class="{ [look]: look }"></span>
		<div class="content" :class="{ disabled: disabled }">
			<slot></slot>
		</div>
	</button>
</template>

<script>
export default {
	props: {
		wide: {
			type: Boolean,
			default: false,
		},
		look: {
			type: String,
			default: "",
		},
		figure: {
			type: String,
			default: "rectangle",
		},
		disabled: {
			type: Boolean,
			default: false,
		},
		type: {
			type: String,
			default: "button",
		},
		minWidth: {
			type: [String, Number, null],
			default: "160px",
		},
	},
	methods: {
		getMinWidth() {
			return this.minWidth;
		},
	},
	mounted() {
		const rootStyles = getComputedStyle(document.documentElement);
		const myVariable = rootStyles.getPropertyValue("--button-min-width").trim();

		if (this.minWidth) {
			switch (typeof this.minWidth) {
				case "string":
					this.$refs.button.style.minWidth = this.minWidth;
					break;
				case "number":
					this.$refs.button.style.minWidth = this.minWidth + "px";
					break;
			}
		} else {
			this.$refs.button.style.minWidth = myVariable;
		}
	},
};
</script>

<style scoped>
/* Кнопка */
.button-default {
	user-select: none;
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;
	gap: var(--button-gap);

	padding: var(--button-padding);
	min-width: var(--button-min-width);
	max-height: var(--button-max-height);
	min-height: var(--button-min-height);
	max-height: var(--button-max-height);
	border: var(--button-border);
	border-radius: var(--button-border-radius);

	font-size: var(--button-font-size);
	color: var(--button-font-color);
	background-color: var(--button-background-color);

	box-shadow: var(--button-box-shadow);
	transition: var(--button-transition);
}

.button-default:hover {
	color: var(--button-font-color-hover);
	background-color: var(--button-background-color-hover);
}

/* secondary */
.button-default.secondary {
	color: var(--button-secondary-font-color);
	background-color: var(--button-secondary-background-color);
}

.button-default.secondary:hover {
	color: var(--button-secondary-font-color-hover);
	background-color: var(--button-secondary-background-color-hover);
}

/* inverse */
.button-default.inverse {
	border: var(--button-inverse-border);

	color: var(--button-inverse-font-color);
	background-color: var(--button-inverse-background-color);
}

.button-default.inverse:hover {
	border: var(--button-inverse-border-hover);

	color: var(--button-inverse-font-color-hover);
	background-color: var(--button-inverse-background-color-hover);
}

/* edit */
.button-default.edit {
	color: var(--button-edit-font-color);
	background-color: var(--button-edit-background-color);
}

.button-default.edit:hover {
	color: var(--button-edit-font-color-hover);
	background-color: var(--button-edit-background-color-hover);
}

/* edit-secondary */
.button-default.edit-secondary {
	color: var(--button-edit-secondary-font-color);
	background-color: var(--button-edit-secondary-background-color);
}

.button-default.edit-secondary:hover {
	color: var(--button-edit-secondary-font-color-hover);
	background-color: var(--button-edit-secondary-background-color-hover);
}

/* delete */
.button-default.delete {
	color: var(--button-delete-font-color);
	background-color: var(--button-delete-background-color);
}

.button-default.delete:hover {
	color: var(--button-delete-font-color-hover);
	background-color: var(--button-delete-background-color-hover);
}

/* delete-secondary */
.button-default.delete-secondary {
	color: var(--button-delete-secondary-font-color);
	background-color: var(--button-delete-secondary-background-color);
}

.button-default.delete-secondary:hover {
	color: var(--button-delete-secondary-font-color-hover);
	background-color: var(--button-delete-secondary-background-color-hover);
}

.wide {
	width: 100%;
}

/* Содержимое */
button > .content {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;
	gap: var(--button-gap);
}

button > .content.disabled {
	visibility: hidden;
	opacity: 0;
}

/* Загрузчик */
.loader {
	position: absolute;

	width: 26px;
	height: 26px;
	border: var(--button-loader-border);
	border-bottom-color: transparent;
	border-radius: 50%;
	display: inline-block;
	box-sizing: border-box;
	animation: rotation 1s linear infinite;
}

.loader.inverse {
	width: 26px;
	height: 26px;
	border: var(--button-loader-inverse-border);
	border-bottom-color: transparent;
	border-radius: 50%;
	display: inline-block;
	box-sizing: border-box;
	animation: rotation 1s linear infinite;
}

.button-default.circle {
	min-width: 0px !important;
	padding: 0px !important;

	width: var(--button-radial-width);
	height: var(--button-radial-height);
	border-radius: var(--button-radial-border-radius);
}

@keyframes rotation {
	0% {
		transform: rotate(0deg);
	}
	100% {
		transform: rotate(360deg);
	}
}
</style>
