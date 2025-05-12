<template>
	<div class="block-once" ref="blockOnce" :class="{ hide: hide }">
		<div class="block-once__head" v-if="$slots.title || $slots.options">
			<div class="block-once__title" v-if="$slots.title">
				<slot name="title"></slot>
			</div>
			<div class="block-once__options" v-if="$slots.options">
				<slot name="options"></slot>
			</div>
		</div>
		<div class="block-once__body" :style="{ minHeight: `${minHeight}px` }">
			<slot name="body"></slot>
		</div>
		<div class="block-once__buttons" v-if="$slots.buttons">
			<slot name="buttons"></slot>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		hide: {
			type: Boolean,
			default: false,
		},
		minHeight: {
			type: Number,
			default: 200,
		},
	},
};
</script>

<style>
.block-once {
	display: flex;
	flex-direction: column;
	gap: 20px;

	box-sizing: border-box;

	background-color: white;
	height: auto;
	border-radius: var(--default-border-radius);

	padding: 20px;

	box-shadow: 0px 4px 10px -10px rgba(0, 0, 0, 0.99);

	transition: all 0.5s;
}

.block-once.hide {
	overflow: hidden;

	max-width: 100%;
}

.block-once__head {
	display: flex;
	justify-content: space-between;
	flex-wrap: wrap;
	align-items: center;
	gap: 10px;
}

.block-once__options {
	display: flex;
	justify-content: flex-end;
	flex-wrap: wrap;
	gap: 10px;

	flex: 1 0 300px;
}

.block-once__body {
	height: 100%;
}

.block-once__buttons {
	display: flex;
	justify-content: flex-end;
	gap: 10px;
}

.block-once__title {
	font-size: 24px;
	font-weight: 500;
	
	flex: 1 0 300px;
}
</style>
