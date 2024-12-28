<template>
	<li :style="nodeMargin" @click="$emit('select', node)" :class="{ all: disabled }">
		<div>{{ node.name }}</div>
		<div class="check" :class="{ active: selected.includes(node) }" v-if="!hasChildren">
			<svg
				xmlns="http://www.w3.org/2000/svg"
				height="26px"
				viewBox="0 -960 960 960"
				width="26px"
			>
				<path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z" />
			</svg>
		</div>
	</li>
	<FilterList
		v-if="hasChildren"
		v-for="child in node.children"
		:node="child"
		:selected="selected"
		:disabled="disabled"
		:spacing="spacing + 30"
		@select="selectNode"
	/>
</template>

<script>
export default {
	name: "FilterList",
	emits: ["select"],
	props: {
		node: {
			type: Object,
			required: true,
		},
		disabled: {
			type: Boolean,
			default: false,
		},
		selected: {
			type: Array,
			required: true,
			default: [],
		},
		spacing: {
			type: Number,
			default: 0,
		},
		status: {
			type: Boolean,
			default: false,
		},
	},
	computed: {
		hasChildren() {
			const { children } = this.node;
			return children && children.length > 0;
		},
		nodeMargin() {
			return {
				"margin-left": `${this.spacing}px`,
			};
		},
	},
	methods: {
		selectNode(node) {
			this.$emit("select", node);
		},
	},
};
</script>
