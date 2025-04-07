<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/schedule">Расписание</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/schedule/specialist">Специалист</router-link>
	</info-bar>

	<block :minHeight="400">
		<div class="specialist">
			<div class="specialist-item">
				<div class="title">01.01.2024 Понедельник</div>
				<div class="content">
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status free">Свободно</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
				</div>
			</div>
			<div class="specialist-item">
				<div class="title">01.01.2024 Понедельник</div>
				<div class="content">
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status free">Свободно</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
				</div>
			</div>
			<div class="specialist-item">
				<div class="title">01.01.2024 Понедельник</div>
				<div class="content">
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status free">Свободно</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
				</div>
			</div>
			<div class="specialist-item">
				<div class="title">01.01.2024 Понедельник</div>
				<div class="content">
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status free">Свободно</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
					<div class="content-item">
						<div class="time">08:00 - 10:00</div>
						<div class="status busy">Занято</div>
					</div>
				</div>
			</div>
		</div>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
	},
	data() {
		return {

      };
	},
};
</script>

<style scoped>
.specialist {
	width: 1250px;
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 20px;
}

.specialist > .specialist-item {
	display: flex;
	flex-direction: column;
	gap: 10px;

	font-size: 18px;
}

.specialist > .specialist-item > .content {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.specialist > .specialist-item > .content > .content-item {
	display: flex;
	justify-content: space-between;
	align-items: center;

	padding: 10px 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 20px;
}

.specialist > .specialist-item > .content > .content-item > .status {
	display: flex;
	justify-content: center;
	align-items: center;

	border-radius: 30px;
	width: 125px;
	height: 40px;
}

.specialist > .specialist-item > .content > .content-item > .status.free {
	background-color: #e5ffe7;
	color: #1faf37;
}

.specialist > .specialist-item > .content > .content-item > .status.busy {
	background-color: #ffe5e5;
	color: #da4444;
}
</style>
