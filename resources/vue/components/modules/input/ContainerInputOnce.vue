<template>
	<div class="container-input-once" :class="look">
		<div class="label" v-if="$slots.label">
			<slot name="label"></slot>
		</div>

		<template v-if="$slots.input">
			<slot name="input"></slot>
		</template>
		<template v-else>
			<template v-if="type == 'phone'">
				<input
					type="tel"
					v-mask="'+7 (###) ###-##-##'"
					:value="modelValue"
					:placeholder="placeholder"
					autocomplete="off"
					@input="$emit('update:modelValue', $event.target.value)"
				/>
			</template>
			<template v-else-if="type == 'password'">
				<input
					:type="hide ? 'password' : 'text'"
					:value="modelValue"
					:placeholder="placeholder"
					autocomplete="off"
					@input="$emit('update:modelValue', $event.target.value)"
				/>

				<div class="icon" @click="changeType">
					<svg
						v-if="hide"
						xmlns="http://www.w3.org/2000/svg"
						height="28px"
						viewBox="0 -960 960 960"
						width="28px"
					>
						<path
							d="m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z"
						/>
					</svg>

					<svg
						v-else
						xmlns="http://www.w3.org/2000/svg"
						height="28px"
						viewBox="0 -960 960 960"
						width="28px"
					>
						<path
							d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"
						/>
					</svg>
				</div>
			</template>
			<template v-else-if="type == 'textarea'">
				<textarea
					:value="modelValue"
					:placeholder="placeholder"
					:autocomplete="autocomplete"
					@input="$emit('update:modelValue', $event.target.value)"
				></textarea>
			</template>
			<template v-else>
				<input
					:type="type"
					:value="modelValue"
					:placeholder="placeholder"
					autocomplete="off"
					@input="$emit('update:modelValue', $event.target.value)"
				/>
			</template>
		</template>

		<div v-if="error" class="error">
			<slot name="error"></slot>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		modelValue: {
			type: [String, Number, Boolean, null],
			default: "",
			required: false,
		},
		type: {
			type: String,
			default: "text",
		},
		placeholder: {
			type: String,
			default: "Введите значение",
		},
		look: {
			type: String,
			default: "",
		},
		autocomplete: {
			type: String,
			default: "off",
		},
		error: {
			type: Boolean,
			default: false,
		},
	},
	data() {
		return {
			hide: true,
		};
	},
	methods: {
		changeType() {
			this.hide = !this.hide;
		},
	},
};
</script>

<style scoped>
.container-input-once {
	position: relative;
	display: flex;
	flex-direction: column;
	gap: var(--input-gap);
}

.container-input-once > .icon {
	cursor: pointer;
	position: absolute;
	bottom: 14px;
	right: 10px;
}

.container-input-once > .icon > svg {
	fill: rgba(0, 0, 0, 0.5);
}

.container-input-once > .icon:hover > svg {
	fill: rgba(0, 0, 0, 1);
}

.container-input-once > .label {
	color: var(--input-label-color);
	font-size: 1.125rem;
}

.container-input-once > :is(input, textarea) {
	box-sizing: border-box;

	border-radius: var(--input-border-radius);
	border: var(--input-border);

	width: 100%;
	height: 54px;

	color: var(--input-color);
	font-size: 1.125rem;

	transition: all 0.2s ease;
}

.container-input-once > input {
	padding: var(--input-padding);

	height: 54px;
}

.container-input-once > textarea {
	resize: none;

	padding: var(--textarea-padding);

	min-height: 150px;
	height: auto;
}

.container-input-once > :is(input, textarea):focus {
	border: var(--input-border-focus);
}

.container-input-once > :is(input, textarea)::placeholder {
	color: var(--input-placeholder-color);
}

.container-input-once > .error {
	user-select: none;
	position: absolute;
	right: 10px;
	top: -10px;
	font-size: 1rem;

	border: var(--input-error-border);
	border-radius: var(--default-border-radius);
	padding: 2.5px 10px;

	background-color: var(--input-error-background-color);
	color: var(--input-error-color);

	animation: show 0.2s ease;
}

/* clear */
.container-input-once.clear > .label {
	color: black;
}
</style>
