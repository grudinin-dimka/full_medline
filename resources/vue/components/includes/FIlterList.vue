<template>
	<div>
		<div v-for="item in array" :key="item.id">
			<h3>{{ item.name }}</h3>
			<FilterList v-if="item.children" :array="item.children" />
		</div>
	</div>
</template>

<script>
export default {
	props: {
		array: {
			type: Array,
			required: true,
			default: [],
		},
	},
};
</script>
