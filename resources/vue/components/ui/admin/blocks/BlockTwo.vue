<template>
	<div class="block-two">
		<div class="item">
			<label>
				<slot name="title-one"></slot>
			</label>
			<slot name="body-one"></slot>
		</div>
		<div class="item">
			<label>
				<slot name="title-two"></slot>
			</label>
			<slot name="body-two"></slot>
		</div>
	</div>
</template>

<script>
export default {};
</script>

<style scoped>
.block-two {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	gap: 20px;
}

.block-two > .item {
	flex: 1 0 350px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	gap: 20px;

	background-color: white;
	height: auto;
	border-radius: 15px;
	padding: 20px;

	max-width: 100%;

	box-shadow: 0px 4px 10px -10px rgba(0, 0, 0, 0.99);
	transition: all 0.5s;
}
</style>
