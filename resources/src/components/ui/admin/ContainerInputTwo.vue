<template>
	<div class="container-inputs-two">
		<div class="item">
			<label>
				<slot name="title-one"></slot>
			</label>
			<slot name="input-one"></slot>
		</div>
		<div class="item">
			<label>
				<slot name="title-two"></slot>
			</label>
			<slot name="input-two"></slot>
		</div>
	</div>
</template>

<script setup></script>

<style>
.container-inputs-two {
	display: grid;
   grid-template-columns: 1fr 1fr;
   gap: 10px;
}

.container-inputs-two > .item {
   display: flex;
   flex-direction: column;
   gap: 5px;
}

.container-inputs-two > .item > label {
	font-size: 18px;
}

.container-inputs-two > .item > input, 
.container-inputs-two > .item > select
{
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	width: 100%;
	height: 58px;

	font-size: 20px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

.container-inputs-two > .item > input:focus {
	border: 2px solid var(--input-border-color-active);
}
</style>
