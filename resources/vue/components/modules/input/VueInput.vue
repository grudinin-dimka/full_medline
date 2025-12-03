<template>
	<vue-input-container>
		<vue-input-label v-if="$slots.label">
			<slot name="label"></slot>
		</vue-input-label>

		<slot name="input"></slot>

		<VueInputError v-if="error" :is-top="!$slots.label">
			<slot name="error"></slot>
		</VueInputError>
	</vue-input-container>
</template>

<script>
import VueInputContainer from "./ui/VueInputContainer.vue";
import VueInputLabel from "./ui/VueInputLabel.vue";
import VueInputError from "./ui/VueInputError.vue";

export default {
	name: "VueInput",
	components: { VueInputContainer, VueInputLabel, VueInputError },
	props: {
		error: {
			type: Boolean,
			default: false,
		},
	},
};
</script>

<style>
.vue-input__icon {
	cursor: pointer;
	position: absolute;
	bottom: 14px;
	right: 10px;
}

.vue-input__icon > svg {
	fill: var(--input-icon-color);
}

.vue-input__icon:hover > svg {
	fill: var(--input-icon-hover-color);
}

.input__icon-search {
	cursor: pointer;
	position: absolute;
	top: 22px;
	right: 18px;

	fill: var(--input-search-icon-color);
}

.vue-input > :is(input, textarea) {
	caret-color: var(--input-caret-color);
	box-sizing: border-box;

	border-radius: var(--input-border-radius);
	border: var(--input-border);

	height: var(--input-height);

	color: var(--input-color);
	font-size: var(--input-font-size);
	font-family: var(--input-font-family);
	background-color: var(--input-background-color);

	transition: var(--input-transition);
}

.vue-input > input {
	padding: var(--input-padding);
}

.vue-input > textarea {
	padding: var(--textarea-padding);

	min-height: var(--textarea-min-height);
	height: var(--textarea-height);
}

@media screen and (max-width: 600px) {
	.vue-input :is(input, textarea) {
		width: 100%;
	}
}

/* Убрать стандартный крестик */
.vue-input input[type="search"]::-webkit-search-cancel-button {
	display: none;
}

.vue-input :is(input, textarea):focus {
	border: var(--input-border-focus);
}

.vue-input :is(input, textarea)::placeholder {
	color: var(--input-placeholder-color);
}
</style>
