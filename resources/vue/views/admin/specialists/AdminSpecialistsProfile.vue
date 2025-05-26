<template>
	<!-- Модальное окно: Специализации -->
	<Modal ref="modalSpecializations" :settings="modalSpecializations">
		<template #title>
			{{ modalSpecializations.values.title }}
		</template>

		<template #body>
			<div class="input-search">
				<ContainerInputSearch
					class="input-search-sticky"
					v-model="search.specializations"
					:placeholder="'Введите специализацию'"
				/>
			</div>

			<!-- Список специализаций -->
			<div class="specializations-list">
				<label
					class="list__checkbox-item"
					v-for="(specialization, index) in getSortedSpecializations"
					:key="specialization.id"
					:class="{ active: cheked.specializations.includes(specialization.id) }"
				>
					<div class="list__checkbox-label">
						<span>
							{{
								index +
								1 +
								paginationSpecializations.elements.range *
									(paginationSpecializations.pages.current - 1) +
								")"
							}}
						</span>

						<span>{{ specialization.name }}</span>
					</div>

					<input
						class="list__checkbox-input"
						type="checkbox"
						:value="specialization.id"
						v-model="cheked.specializations"
					/>

					<div class="list__checkbox-icon" aria-hidden="true">
						<div class="list__checkbox-icon--checked">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="30px"
								viewBox="0 -960 960 960"
								width="30px"
							>
								<path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z" />
							</svg>
						</div>
						<div class="list__checkbox-icon--unchecked"></div>
					</div>
				</label>
			</div>

			<!-- Пагинация -->
			<!-- <Pagination
				:settings="paginationSpecializations"
				:arrayLength="sections.specializations.length"
				@changePage="changePageSpecializations"
			/> -->
		</template>

		<template #footer>
			<button-default @click="updateSpecialization">
				<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
				Добавить
			</button-default>
		</template>
	</Modal>

	<!-- Модальное окно: Клиники -->
	<Modal ref="modalClinics" :settings="modalClinics">
		<template #title>
			{{ modalClinics.values.title }}
		</template>

		<template #body>
			<div class="input-search">
				<ContainerInputSearch
					class="input-search-sticky"
					v-model="search.clinics"
					:placeholder="'Введите клинику'"
				/>
			</div>

			<!-- Список специализаций -->
			<div class="clinics__list">
				<div
					class="clinics__list-item"
					v-for="(clinic, index) in getSortedClinics"
					:key="clinic.id"
					:class="{ active: cheked.clinics.includes(clinic.id) }"
				>
					<label
						class="list__checkbox-item"
						:class="{ active: cheked.clinics.includes(clinic.id) }"
					>
						<div class="list__checkbox-label">
							<span>
								{{
									index +
									1 +
									paginationClinics.elements.range *
										(paginationClinics.pages.current - 1) +
									")"
								}}
							</span>

							<span>{{ clinic.name }}</span>
						</div>

						<input
							class="list__checkbox-input"
							type="checkbox"
							:value="clinic.id"
							v-model="cheked.clinics"
						/>

						<div class="list__checkbox-icon" aria-hidden="true">
							<div class="list__checkbox-icon--checked">
								<svg
									xmlns="http://www.w3.org/2000/svg"
									height="30px"
									viewBox="0 -960 960 960"
									width="30px"
								>
									<path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z" />
								</svg>
							</div>
							<div class="list__checkbox-icon--unchecked"></div>
						</div>
					</label>

					<select v-model="clinic.priem">
						<option :value="0">Нет</option>
						<option :value="1">Да</option>
					</select>
				</div>
			</div>

			<!-- Пагинация -->
			<!-- <Pagination
				:settings="paginationClinics"
				:arrayLength="sections.clinics.length"
				@changePage="changePageClinics"
			/> -->
		</template>

		<template #footer>
			<button-default @click="updateClinics">
				<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
				Добавить
			</button-default>
		</template>
	</Modal>

	<!-- Модальное окно: Сертификат -->
	<Modal ref="modalCertificates" :settings="modalCertificates">
		<template #title>
			{{ modalCertificates.values.title }}
		</template>

		<template #body>
			<container-input-once>
				<template #title>
					<span>НАЗВАНИЕ*</span>
					<span v-if="currentCertificate.data.name.edited"> (ИЗМЕНЕНО) </span>
				</template>
				<template #input>
					<input
						type="text"
						placeholder="Название должности"
						:class="{ error: currentCertificate.errors.name.status }"
						v-model="currentCertificate.data.name.value"
						@input="currentCertificate.data.name.edited = true"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentCertificate.errors.name.message">
						{{ currentCertificate.errors.name.message }}
					</span>
				</template>
			</container-input-once>
			<!-- Организация -->
			<container-input-once>
				<template #title>
					<span>ОРГАНИЗАЦИЯ*</span>
					<span v-if="currentCertificate.data.organization.edited"> (ИЗМЕНЕНО) </span>
				</template>
				<template #input>
					<input
						type="text"
						placeholder="Название организации"
						:class="{ error: currentCertificate.errors.organization.status }"
						v-model="currentCertificate.data.organization.value"
						@input="currentCertificate.data.organization.edited = true"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentCertificate.errors.organization.message">
						{{ currentCertificate.errors.organization.message }}
					</span>
				</template>
			</container-input-once>
			<!-- Дата окончания обучения -->
			<container-input-once>
				<template #title>
					<span>ОКОНЧАНИЕ ОБУЧЕНИЯ*</span>
					<span v-if="currentCertificate.data.endEducation.edited"> (ИЗМЕНЕНО) </span>
				</template>
				<template #input>
					<input
						type="date"
						placeholder="Введите дату"
						:class="{ error: currentCertificate.errors.endEducation.status }"
						v-model="currentCertificate.data.endEducation.value"
						@input="currentCertificate.data.endEducation.edited = true"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentCertificate.errors.endEducation.message">
						{{ currentCertificate.errors.endEducation.message }}
					</span>
				</template>
			</container-input-once>
		</template>

		<template #footer>
			<template v-if="modalCertificates.values.look == 'create'">
				<button-default @click="addCertificate">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
			</template>

			<template v-if="modalCertificates.values.look == 'default'">
				<button-default @click="updateCertificate">
					<Icon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</button-default>
			</template>
		</template>
	</Modal>

	<!-- Модальное окно: Образования -->
	<Modal ref="modalEducations" :settings="modalEducations">
		<template #title>
			{{ modalEducations.values.title }}
		</template>

		<template #body>
			<container-input>
				<!-- Название -->
				<container-input-once>
					<template #title>
						<span>НАЗВАНИЕ*</span>
						<span v-if="currentEducation.data.name.edited"> (ИЗМЕНЕНО) </span>
					</template>
					<template #input>
						<input
							type="text"
							placeholder="Введите название"
							autocomplete="off"
							:class="{ error: currentEducation.errors.name.status }"
							v-model="currentEducation.data.name.value"
							@input="currentEducation.data.name.edited = true"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentEducation.errors.name.status">
							{{ currentEducation.errors.name.message }}
						</span>
					</template>
				</container-input-once>
				<!-- Организация -->
				<container-textarea-once>
					<template #title>
						<span>ОРГАНИЗАЦИЯ*</span>
						<span v-if="currentEducation.data.organization.edited"> (ИЗМЕНЕНО) </span>
					</template>
					<template #textarea>
						<textarea
							rows="4"
							placeholder="Введите организацию"
							autocomplete="off"
							:class="{ error: currentEducation.errors.organization.status }"
							v-model="currentEducation.data.organization.value"
							@input="currentEducation.data.organization.edited = true"
						></textarea>
					</template>
					<template #error>
						<span class="error" v-if="currentEducation.errors.organization.status">
							{{ currentEducation.errors.organization.message }}
						</span>
					</template>
				</container-textarea-once>
				<!-- Дата получения образования -->
				<container-input-once>
					<template #title>
						<span>ДАТА ПОЛУЧЕНИЯ*</span>
						<span v-if="currentEducation.data.date.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input>
						<input
							type="date"
							placeholder="Введите организацию"
							autocomplete="off"
							:class="{ error: currentEducation.errors.date.status }"
							v-model="currentEducation.data.date.value"
							@input="currentEducation.data.date.edited = true"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentEducation.errors.date.status">
							{{ currentEducation.errors.date.message }}
						</span>
					</template>
				</container-input-once>
				<!-- Выбор специализации -->
				<container-input-once>
					<template #title>
						<span>СПЕЦИАЛИЗАЦИЯ*</span>
						<span v-if="currentEducation.data.speсialization.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input>
						<input
							type="text"
							placeholder="Введите специализацию"
							autocomplete="off"
							list="eduacation-specializations"
							:class="{ error: currentEducation.errors.speсialization.status }"
							v-model="currentEducation.data.speсialization.value"
							@input="currentEducation.data.speсialization.edited = true"
						/>
						<datalist id="eduacation-specializations">
							<option value="Лечебное дело"></option>
							<option value="Педиатрия"></option>
							<option value="Сестринское дело"></option>
							<option value="Фармация"></option>
						</datalist>
					</template>
					<template #error>
						<span class="error" v-if="currentEducation.errors.speсialization.status">
							{{ currentEducation.errors.speсialization.message }}
						</span>
					</template>
				</container-input-once>
			</container-input>
		</template>

		<template #footer>
			<template v-if="modalEducations.values.look == 'create'">
				<button-default @click="addEducation">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
			</template>

			<template v-if="modalEducations.values.look == 'default'">
				<button-default @click="updateEducation">
					<Icon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</button-default>
			</template>
		</template>
	</Modal>

	<!-- Модальное окно: Работы -->
	<Modal ref="modalWorks" :settings="modalWorks">
		<template #title>
			{{ modalWorks.values.title }}
		</template>

		<template #body>
			<container-input>
				<!-- Название -->
				<container-input-once>
					<template #title>
						<span>НАЗВАНИЕ*</span>
						<span v-if="currentWork.data.name.edited"> (ИЗМЕНЕНО) </span>
					</template>
					<template #input>
						<input
							type="text"
							placeholder="Название работы"
							v-model="currentWork.data.name.value"
							:class="{ error: currentWork.errors.name.status }"
							@input="currentWork.data.name.edited = true"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentWork.errors.name.status">
							{{ currentWork.errors.name.message }}
						</span>
					</template>
				</container-input-once>
				<!-- Организация -->
				<container-textarea-once>
					<template #title>
						<span>ОРГАНИЗАЦИЯ*</span>
						<span v-if="currentWork.data.organization.edited"> (ИЗМЕНЕНО) </span>
					</template>
					<template #textarea>
						<textarea
							rows="4"
							placeholder="Название организации"
							v-model="currentWork.data.organization.value"
							:class="{ error: currentWork.errors.organization.status }"
							@input="currentWork.data.organization.edited = true"
						></textarea>
					</template>
					<template #error>
						<span class="error" v-if="currentWork.errors.organization.status">
							{{ currentWork.errors.organization.message }}
						</span>
					</template>
				</container-textarea-once>
				<!-- Начало и конец -->
				<container-input-two :fieldset="true">
					<template #legend>
						<span>НАЧАЛО И ОКОНЧАНИЕ РАБОТЫ</span>
					</template>
					<!-- Начало работы -->
					<template #title-one>
						<span>ДАТА НАЧАЛА*</span>
						<span v-if="currentWork.data.startWork.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							type="date"
							autocomplete="off"
							v-model="currentWork.data.startWork.value"
							:class="{ error: currentWork.errors.startWork.status }"
							@input="currentWork.data.startWork.edited = true"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentWork.errors.startWork.status">
							{{ currentWork.errors.startWork.message }}
						</span>
					</template>
					<!-- Конец работы -->
					<template #title-two>
						<span>ДАТА ОКОНЧАНИЯ*</span>
						<span v-if="currentWork.data.endWork.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input
							type="date"
							placeholder="Введите улицу"
							autocomplete="off"
							v-model="currentWork.data.endWork.value"
							:class="{ error: currentWork.errors.endWork.status }"
							@input="currentWork.data.endWork.edited = true"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentWork.errors.endWork.status">
							{{ currentWork.errors.endWork.message }}
						</span>
					</template>
				</container-input-two>
			</container-input>
		</template>
		<template #footer>
			<template v-if="modalWorks.values.look == 'create'">
				<button-default @click="addWork">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
			</template>

			<template v-if="modalWorks.values.look == 'default'">
				<button-default @click="updateWork">
					<Icon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</button-default>
			</template>
		</template>
	</Modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                   СПЕЦИАЛИСТ                      |-->
	<!--|___________________________________________________|-->
	<!--____________________________________________________-->
	<!--1. Профиль                                          -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists/{{ $route.params.id }}</template>
	</info-bar>

	<!-- Основные данные врача -->
	<block-once :minHeight="400">
		<template #title>
			<span>ПРОФИЛЬ</span>
		</template>

		<template #options>
			<template v-if="$route.params.id === 'new'">
				<button-default
					@click.prevent="addSpecialist"
					:disabled="disabled.profile.create"
					:look="'white'"
				>
					<Icon
						:name="'add'"
						:fill="'var(--primary-color)'"
						:width="'23px'"
						:height="'23px'"
					/>
					Добавить
				</button-default>
			</template>

			<template v-else>
				<button-default
					@click.prevent="saveSpecialistModular('all')"
					:disabled="disabled.profile.save"
					:look="'white'"
				>
					<Icon
						:name="'save'"
						:fill="'var(--primary-color)'"
						:width="'28px'"
						:height="'28px'"
					/>
					Сохранить
				</button-default>
			</template>
		</template>

		<template #body>
			<div class="container-profile" v-show="loading.sections.profile">
				<div class="profile-image" v-if="$route.params.id == 'new'">
					<div
						class="clinics__list-head"
						:style="{
							backgroundImage: `url(/storage/default/image-none-default.png)`,
						}"
					></div>
				</div>
				<div class="profile-image" v-else>
					<div
						class="clinics__list-head"
						:style="{
							backgroundImage: `url(${specialist.profile.data.path.value})`,
						}"
					></div>
				</div>
				<div class="profile-info">
					<container-input-two :fieldset="true">
						<template #legend>
							<span>АВАТАР И ССЫЛКА</span>
						</template>
						<template #title-one>
							<span
								>ФОТО ВРАЧА (700x700)<span v-if="$route.params.id == 'new'">*</span></span
							>
							<span v-if="specialist.profile.data.filename.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #input-one>
							<input
								class="profile-file"
								type="file"
								autocomplete="off"
								ref="fileUpload"
								:class="{ error: specialist.profile.errors.file.status }"
							/>
						</template>
						<template #error-one>
							<span class="error" v-if="specialist.profile.errors.file.status">
								{{ specialist.profile.errors.file.message }}
							</span>
						</template>
						<template #title-two>
							<span>ССЫЛКА НА ПРОДОКТОРОВ*</span>
							<span v-if="specialist.profile.data.link.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #input-two>
							<input
								type="text"
								placeholder="Введите ссылку"
								autocomplete="off"
								v-model="specialist.profile.data.link.value"
								:class="{ error: specialist.profile.errors.link.status }"
								@input="specialist.profile.data.link.edited = true"
							/>
						</template>
						<template #error-two>
							<span class="error" v-if="true">
								{{ specialist.profile.errors.link.message }}
							</span>
						</template>
					</container-input-two>

					<container-input-three :fieldset="true">
						<template #legend>
							<span>Ф.И.О.</span>
						</template>
						<template #title-one>
							<span>ФАМИЛИЯ*</span>
							<span v-if="specialist.profile.data.family.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #input-one>
							<input
								type="text"
								placeholder="Введите фамилию"
								autocomplete="off"
								v-model="specialist.profile.data.family.value"
								:class="{ error: specialist.profile.errors.family.status }"
								@input="specialist.profile.data.family.edited = true"
							/>
						</template>
						<template #error-one>
							<span class="error" v-if="specialist.profile.errors.family.status">
								{{ specialist.profile.errors.family.message }}
							</span>
						</template>
						<template #title-two>
							<span>ИМЯ*</span>
							<span v-if="specialist.profile.data.name.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #input-two>
							<input
								type="text"
								placeholder="Введите имя"
								autocomplete="off"
								v-model="specialist.profile.data.name.value"
								:class="{ error: specialist.profile.errors.name.status }"
								@input="specialist.profile.data.name.edited = true"
							/>
						</template>
						<template #error-two>
							<span class="error" v-if="specialist.profile.errors.name.status">
								{{ specialist.profile.errors.name.message }}
							</span>
						</template>
						<template #title-three>
							<span>ОТЧЕСТВО</span>
							<span v-if="specialist.profile.data.surname.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #input-three>
							<input
								type="text"
								placeholder="Введите отчество"
								autocomplete="off"
								v-model="specialist.profile.data.surname.value"
								:class="{ error: specialist.profile.errors.surname.status }"
								@input="specialist.profile.data.surname.edited = true"
							/>
						</template>
						<template #error-three>
							<span class="error" v-if="specialist.profile.errors.surname.status">
								{{ specialist.profile.errors.surname.message }}
							</span>
						</template>
					</container-input-three>
				</div>
			</div>
			<div class="container-profile-other" v-show="loading.sections.profile">
				<container-input>
					<container-select-three :fieldset="true">
						<template #legend>
							<span>НАУЧНОЕ ОБРАЗОВАНИЕ</span>
						</template>
						<template #title-one>
							<span>КАТЕГОРИЯ*</span>
							<span v-if="specialist.profile.data.category.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #select-one>
							<Selector
								v-model="specialist.profile.data.category.value"
								:placeholder="'Выберите категорию'"
								:type="specialist.profile.errors.category.status ? 'error' : 'default'"
								:filter="false"
								:list="[
									{
										value: 'Нет категории',
										label: 'Нет категории',
									},
									{
										value: 'Первая',
										label: 'Первая',
									},
									{
										value: 'Вторая',
										label: 'Вторая',
									},
									{
										value: 'Высшая',
										label: 'Высшая',
									},
								]"
							></Selector>
						</template>
						<template #error-one>
							<span class="error" v-if="specialist.profile.errors.category.status">
								{{ specialist.profile.errors.category.message }}
							</span>
						</template>
						<template #title-two>
							<span>СТЕПЕНЬ</span>
							<span v-if="specialist.profile.data.degree.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #select-two>
							<input
								type="text"
								placeholder="Введите степень"
								autocomplete="off"
								list="specialist-dedgree"
								v-model="specialist.profile.data.degree.value"
								@input="specialist.profile.data.degree.edited = true"
							/>
							<datalist id="specialist-dedgree">
								<option value="Отсутствует">Отсутствует</option>
								<option value="Кандидат медицинских наук">Кандидат медицинских наук</option>
								<option value="Доктор медицинских наук">Доктор медицинских наук</option>
							</datalist>
						</template>
						<template #title-three>
							<span>ЗВАНИЕ</span>
							<span v-if="specialist.profile.data.rank.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #select-three>
							<input
								type="text"
								placeholder="Введите звание"
								autocomplete="off"
								list="specialist-rank"
								v-model="specialist.profile.data.rank.value"
								@input="specialist.profile.data.rank.edited = true"
							/>
							<datalist id="specialist-rank">
								<option value="Отсутствует">Отсутствует</option>
								<option value="Доцент">Доцент</option>
								<option value="Профессор">Профессор</option>
							</datalist>
						</template>
					</container-select-three>
					<!-- Первая работа -->
					<container-input-two :fieldset="true">
						<template #legend>
							<span>НАЧАЛО КАРЪЕРЫ И ГОРОД ПРИЕМА</span>
						</template>
						<template #title-one>
							<span
								>ДАТА* ({{ getAge(specialist.profile.data.startWorkAge.value) }} лет)</span
							>
							<span v-if="specialist.profile.data.startWorkAge.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #input-one>
							<input
								type="date"
								autocomplete="off"
								v-model="specialist.profile.data.startWorkAge.value"
								:class="{ error: specialist.profile.errors.startWorkAge.status }"
								@input="specialist.profile.data.startWorkAge.edited = true"
							/>
						</template>
						<template #error-one>
							<span class="error" v-if="specialist.profile.errors.startWorkAge.status">
								{{ specialist.profile.errors.startWorkAge.message }}
							</span>
						</template>

						<template #title-two>
							<span>ГОРОД</span>
							<span v-if="specialist.profile.data.startWorkCity.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #input-two>
							<input
								type="text"
								placeholder="Введите название города"
								autocomplete="off"
								:class="{ error: specialist.profile.errors.startWorkCity.status }"
								v-model="specialist.profile.data.startWorkCity.value"
								@input="specialist.profile.data.startWorkCity.edited = true"
							/>
						</template>
						<template #error-two>
							<span class="error" v-if="specialist.profile.errors.startWorkCity.status">
								{{ specialist.profile.errors.startWorkCity.message }}
							</span>
						</template>
					</container-input-two>
					<!-- Статус приёма -->
					<container-input-two-sub
						:fieldset="true"
						:subOne="false"
						:subTwo="specialist.profile.data.childrenDoctor.value ? true : false"
					>
						<template #legend>
							<span>ПРИЁМ ВРАЧА</span>
						</template>
						<template #title-one>
							<span>У ВЗРОСЛЫХ*</span>
							<span v-if="specialist.profile.data.adultDoctor.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #input-one>
							<Selector
								v-model="specialist.profile.data.adultDoctor.value"
								:placeholder="'Выберите статус'"
								:type="specialist.profile.errors.adultDoctor.status ? 'error' : 'default'"
								:filter="false"
								:list="[
									{
										value: 0,
										label: 'Нет',
									},
									{
										value: 1,
										label: 'Да',
									},
								]"
							></Selector>
						</template>
						<template #error-one>
							<span class="error" v-if="specialist.profile.errors.adultDoctor.status">
								{{ specialist.profile.errors.adultDoctor.message }}
							</span>
						</template>
						<template #title-two>
							<span>У ДЕТЕЙ*</span>
							<span v-if="specialist.profile.data.childrenDoctor.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #input-two>
							<Selector
								v-model="specialist.profile.data.childrenDoctor.value"
								:placeholder="'Выберите статус'"
								:type="
									specialist.profile.errors.childrenDoctor.status ? 'error' : 'default'
								"
								:filter="false"
								:list="[
									{
										value: 0,
										label: 'Нет',
									},
									{
										value: 1,
										label: 'Да',
									},
								]"
							></Selector>
						</template>
						<template #error-two>
							<span class="error" v-if="specialist.profile.errors.childrenDoctor.status">
								{{ specialist.profile.errors.childrenDoctor.message }}
							</span>
						</template>
						<template #title-sub-two>
							<span>ВОЗРАСТ* (+{{ specialist.profile.data.childrenDoctorAge.value }})</span>
							<span v-if="specialist.profile.data.childrenDoctorAge.edited">
								(ИЗМЕНЕНО)</span
							>
						</template>
						<template #input-sub-two>
							<input
								type="number"
								placeholder="Годы"
								v-model="specialist.profile.data.childrenDoctorAge.value"
								:class="{ error: specialist.profile.errors.childrenDoctorAge.status }"
								@input="specialist.profile.data.childrenDoctorAge.edited = true"
							/>
						</template>
						<template #error-sub-two>
							<span class="error" v-if="specialist.profile.errors.childrenDoctorAge.status">
								{{ specialist.profile.errors.childrenDoctorAge.message }}
							</span>
						</template>
					</container-input-two-sub>
				</container-input>

				<Tiptap
					ref="tiptapDescription"
					v-model="specialist.profile.data.description.value"
					:editable="true"
					:minHeight="200"
					:limit="10_000"
					:options="['format', 'align', 'list', 'link']"
					:placeholder="'Описание'"
				/>
			</div>
			<!-- Загрузчик профиля -->
			<loader-child
				:isLoading="loading.loader.profile"
				:minHeight="600"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>

	<template v-if="$route.params.id === 'new'">
		<block-two :minHeight="40">
			<template #one-title> СПЕЦИАЛИЗАЦИИ </template>

			<template #one-body>
				<div class="profile-list" v-show="loading.sections.specializations">
					<div class="item-empty">
						<div class="item-title">Необходимо создать врача</div>
					</div>
				</div>

				<!-- Загрузчик специализаций -->
				<loader-child
					:isLoading="loading.loader.specializations"
					:minHeight="100"
					@loaderChildAfterLeave="loaderChildAfterLeave"
				/>
			</template>

			<template #two-title> КЛИНИКИ </template>

			<template #two-body>
				<div class="profile-list" v-show="loading.sections.clinics">
					<div class="item-empty">
						<div class="item-title">Необходимо создать врача</div>
					</div>
				</div>

				<!-- Загрузчик клиник -->
				<loader-child
					:isLoading="loading.loader.clinics"
					:minHeight="100"
					@loaderChildAfterLeave="loaderChildAfterLeave"
				/>
			</template>
		</block-two>

		<block-once :minHeight="50">
			<template #title> СЕРТИФИКАТЫ </template>

			<template #body>
				<div class="profile-list" v-show="loading.sections.certificates">
					<div class="item-empty">
						<div class="item-title">Необходимо создать врача</div>
					</div>
				</div>

				<!-- Загрузчик профиля -->
				<loader-child
					:isLoading="loading.loader.certificates"
					:minHeight="100"
					@loaderChildAfterLeave="loaderChildAfterLeave"
				/>
			</template>
		</block-once>

		<block-once :minHeight="50">
			<template #title> ОБРАЗОВАНИЕ </template>

			<template #body>
				<div class="profile-list" v-show="loading.sections.educations">
					<div class="item-empty">
						<div class="item-title">Необходимо создать врача</div>
					</div>
				</div>

				<!-- Загрузчик профиля -->
				<loader-child
					:isLoading="loading.loader.educations"
					:minHeight="100"
					@loaderChildAfterLeave="loaderChildAfterLeave"
				/>
			</template>
		</block-once>

		<block-once :minHeight="50">
			<template #title> МЕСТА РАБОТЫ </template>

			<template #body>
				<div class="profile-list" v-show="loading.sections.works">
					<div class="item-empty">
						<div class="item-title">Необходимо создать врача</div>
					</div>
				</div>

				<!-- Загрузчик профиля -->
				<loader-child
					:isLoading="loading.loader.works"
					:minHeight="100"
					@loaderChildAfterLeave="loaderChildAfterLeave"
				/>
			</template>
		</block-once>
	</template>

	<template v-else>
		<block-two :minHeight="40">
			<!--____________________________________________________-->
			<!--2. Специализации                                    -->
			<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
			<template #one-title>
				СПЕЦИАЛИЗАЦИИ
				<span v-show="loading.sections.specializations">
					({{ specialist.connections.specializations.length }})
				</span>
			</template>

			<template #one-body>
				<div class="profile-list" v-show="loading.sections.specializations">
					<!-- Если специализации не выбраны -->
					<div class="item-empty" v-if="specialist.connections.specializations.length == 0">
						<div class="item-title">Пока тут ничего нет...</div>
					</div>
					<!-- Если специализации выбраны -->
					<template v-else>
						<div class="clinics__list-head">
							<div>Название</div>
						</div>
						<div
							class="clinics__list-head"
							v-for="specialization in sortedConnectionsSpecializations"
						>
							<div class="item-title">
								{{
									sections.specializations.filter(
										(item) => item.id == specialization.id_specialization
									)[0].name
								}}
							</div>
							<div class="item-priem"></div>
							<div
								class="item-close"
								@click="removeArrValue('specializations', specialization)"
							>
								<icon-close :width="26" :height="26" />
							</div>
						</div>
					</template>
				</div>

				<!-- Загрузчик специализаций -->
				<loader-child
					:isLoading="loading.loader.specializations"
					:minHeight="100"
					@loaderChildAfterLeave="loaderChildAfterLeave"
				/>
			</template>

			<template #one-buttons>
				<button-default @click="openModalSpecializationsEdite">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
			</template>

			<!--____________________________________________________-->
			<!--3. Клиники                                          -->
			<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
			<template #two-title>
				КЛИНИКИ
				<span v-show="loading.sections.clinics">
					({{ specialist.connections.clinics.length }})
				</span>
			</template>

			<template #two-body>
				<div class="profile-list" v-show="loading.sections.clinics">
					<!-- Если клиники не выбраны -->
					<div class="item-empty" v-if="specialist.connections.clinics.length == 0">
						<div class="item-title">Пока тут ничего нет...</div>
					</div>
					<template v-else>
						<div class="clinics__list-head">
							<div>Название</div>
							<div>Приём</div>
							<div></div>
						</div>
						<!-- Если клиники выбраны -->
						<div
							class="clinics__list-head"
							v-for="clinic in specialist.connections.clinics"
							:key="clinic.id"
						>
							<div class="item-title">
								{{ sections.clinics.filter((item) => item.id == clinic.id_clinic)[0].name }}
							</div>
							<div class="item-priem">
								{{ clinic.priem ? `Да` : "Нет" }}
							</div>
							<div class="item-close" @click="removeArrValue('clinics', clinic)">
								<icon-close :width="26" :height="26" />
							</div>
						</div>
					</template>
				</div>

				<!-- Загрузчик клиник -->
				<loader-child
					:isLoading="loading.loader.clinics"
					:minHeight="100"
					@loaderChildAfterLeave="loaderChildAfterLeave"
				/>
			</template>

			<template #two-buttons>
				<button-default @click="openModalClinicsEdite">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
			</template>
		</block-two>

		<!--____________________________________________________-->
		<!--4. Сертификаты                                      -->
		<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
		<block-once :minHeight="100">
			<template #title>
				СЕРТИФИКАТЫ
				<span v-show="loading.sections.certificates">
					({{ tableCertificates.body.length }})
				</span>
			</template>

			<template #body>
				<BaseTable
					v-if="loading.sections.certificates"
					:table="tableCertificates"
					@create="openModalCertificatesCreate"
					@edite="openModalCertificatesEdite"
					@delete="setFlagDelete"
				/>

				<!-- Загрузчик профиля -->
				<loader-child
					:isLoading="loading.loader.clinics"
					:minHeight="100"
					@loaderChildAfterLeave="loaderChildAfterLeave"
				/>
			</template>
		</block-once>
		<!--____________________________________________________-->
		<!--5. Образование                                      -->
		<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
		<block-once :minHeight="100">
			<template #title>
				ОБРАЗОВАНИЕ
				<span v-show="loading.sections.educations"> ({{ tableEducations.body.length }}) </span>
			</template>

			<template #body>
				<BaseTable
					v-if="loading.sections.educations"
					:table="tableEducations"
					@create="openModalEducationsCreate"
					@edite="openModalEducationsEdite"
					@delete="setFlagDelete"
				/>

				<!-- Загрузчик профиля -->
				<loader-child
					:isLoading="loading.loader.clinics"
					:minHeight="100"
					@loaderChildAfterLeave="loaderChildAfterLeave"
				/>
			</template>
		</block-once>

		<!--____________________________________________________-->
		<!--6. Места работы                                     -->
		<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
		<block-once :minHeight="100">
			<template #title>
				МЕСТА РАБОТЫ
				<span v-show="loading.sections.works"> ({{ tableWorks.body.length }}) </span>
			</template>

			<template #body>
				<BaseTable
					v-if="loading.sections.works"
					:table="tableWorks"
					@create="openModalWorksCreate"
					@edite="openModalWorksEdite"
					@delete="setFlagDelete"
				/>

				<!-- Загрузчик профиля -->
				<loader-child
					:isLoading="loading.loader.clinics"
					:minHeight="100"
					@loaderChildAfterLeave="loaderChildAfterLeave"
				/>
			</template>
		</block-once>
	</template>
</template>

<script>
import Modal from "../../../components/modules/modal/Modal.vue";
import BaseTable from "../../../components/modules/table/BaseTable.vue";
import Tiptap from "../../../components/modules/Tiptap.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import Selector from "../../../components/modules/Selector.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTwo from "../../../components/ui/admin/blocks/BlockTwo.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputSearch from "../../../components/ui/admin/containers/input/ContainerInputSearch.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/containers/input/ContainerInputTwo.vue";
import ContainerInputTwoSub from "../../../components/ui/admin/containers/input/ContainerInputTwoSub.vue";
import ContainerInputThree from "../../../components/ui/admin/containers/input/ContainerInputThree.vue";
import ContainerSelectOnce from "../../../components/ui/admin/containers/select/ContainerSelectOnce.vue";
import ContainerSelectThree from "../../../components/ui/admin/containers/select/ContainerSelectThree.vue";
import ContainerTextareaOnce from "../../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonDisabled from "../../../components/ui/admin/buttons/ButtonDisabled.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import Pagination from "../../../components/modules/Pagination.vue";

import Icon from "../../../components/modules/icon/Icon.vue";
import IconClose from "../../../components/icons/IconClose.vue";

import api from "../../../services/api";
import validate from "../../../services/validate";
import files from "../../../services/files";
import shared from "../../../services/shared";
import sorted from "../../../services/sorted";

import { RouterView, RouterLink } from "vue-router";

export default {
	components: {
		Modal,
		BaseTable,
		Tiptap,

		InfoBar,
		Selector,
		LoaderChild,

		BlockOnce,
		BlockTwo,

		ContainerInput,
		ContainerInputSearch,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputTwoSub,
		ContainerInputThree,
		ContainerSelectOnce,
		ContainerSelectThree,
		ContainerTextareaOnce,

		ButtonDefault,
		ButtonDisabled,
		ButtonRemove,
		ButtonClaim,

		Pagination,

		Icon,
		IconClose,

		RouterView,
		RouterLink,
	},
	data() {
		return {
			disabled: {
				profile: {
					save: false,
					create: false,
				},
			},

			/* Модальное окно */
			modalSpecializations: {
				thin: false,
				clamped: true,
				values: {
					title: "",
					look: "default",
				},
			},

			modalClinics: {
				thin: false,
				clamped: true,
				values: {
					title: "",
					look: "default",
				},
			},

			modalCertificates: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalEducations: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalWorks: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			currentCertificate: {
				errors: {
					id: {
						message: "",
						status: false,
					},
					organization: {
						message: "",
						status: false,
					},
					endEducation: {
						message: "",
						status: false,
					},
					name: {
						message: "",
						status: false,
					},
				},
				data: {
					id: {
						value: "",
						edited: false,
					},
					organization: {
						value: "",
						edited: false,
					},
					endEducation: {
						value: "",
						edited: false,
					},
					name: {
						value: "",
						edited: false,
					},
					create: {
						value: false,
						edited: false,
					},
					delete: {
						value: false,
						edited: false,
					},
				},
			},

			currentEducation: {
				errors: {
					id: {
						message: null,
						status: false,
					},
					name: {
						message: null,
						status: false,
					},
					organization: {
						message: null,
						status: false,
					},
					date: {
						message: null,
						status: false,
					},
					speсialization: {
						message: null,
						status: false,
					},
					create: {
						message: false,
						status: false,
					},
					delete: {
						message: false,
						status: false,
					},
				},
				data: {
					id: {
						value: null,
						edited: false,
					},
					name: {
						value: null,
						edited: false,
					},
					organization: {
						value: null,
						edited: false,
					},
					date: {
						value: null,
						edited: false,
					},
					speсialization: {
						value: null,
						edited: false,
					},
					create: {
						value: false,
						edited: false,
					},
					delete: {
						value: false,
						edited: false,
					},
				},
			},

			currentWork: {
				errors: {
					id: {
						message: "",
						status: false,
					},
					startWork: {
						message: "",
						status: false,
					},
					endWork: {
						message: "",
						status: false,
					},
					organization: {
						message: "",
						status: false,
					},
					name: {
						message: "",
						status: false,
					},
				},
				data: {
					id: {
						value: "",
						edited: false,
					},
					startWork: {
						value: "",
						edited: false,
					},
					endWork: {
						value: "",
						edited: false,
					},
					organization: {
						value: "",
						edited: false,
					},
					name: {
						value: "",
						edited: false,
					},
					create: {
						value: false,
						edited: false,
					},
					delete: {
						value: false,
						edited: false,
					},
				},
			},

			/* Загрузчик */
			loading: {
				loader: {
					profile: true,
					certificates: true,
					specializations: true,
					clinics: true,
					educations: true,
					works: true,
				},
				sections: {
					profile: false,
					certificates: false,
					specializations: false,
					clinics: false,
					educations: false,
					works: false,
				},
			},

			/* Пагинация */
			paginationSpecializations: {
				pages: {
					range: 5,
					current: 1,
				},
				elements: {
					range: 40,
				},
			},

			paginationClinics: {
				pages: {
					range: 5,
					current: 1,
				},
				elements: {
					range: 40,
				},
			},

			/* Специалист */
			specialist: {
				// Основная информация
				profile: {
					errors: {
						file: {
							message: "",
							status: false,
						},
						id: {
							message: "",
							status: false,
						},
						link: {
							message: "",
							status: false,
						},
						family: {
							message: "",
							status: false,
						},
						name: {
							message: "",
							status: false,
						},
						surname: {
							message: "",
							status: false,
						},
						description: {
							message: "",
							status: false,
						},
						category: {
							message: "",
							status: false,
						},
						degree: {
							message: "",
							status: false,
						},
						rank: {
							message: "",
							status: false,
						},
						startWorkAge: {
							message: "",
							status: false,
						},
						startWorkCity: {
							message: "",
							status: false,
						},
						adultDoctor: {
							message: "",
							status: false,
						},
						childrenDoctor: {
							message: "",
							status: false,
						},
						childrenDoctorAge: {
							message: "",
							status: false,
						},
						hide: {
							message: "",
							status: false,
						},
						filename: {
							message: "",
							status: false,
						},
						path: {
							message: "",
							status: false,
						},
					},
					data: {
						id: {
							value: "",
							edited: false,
						},
						link: {
							value: "",
							edited: false,
						},
						family: {
							value: "",
							edited: false,
						},
						name: {
							value: "",
							edited: false,
						},
						surname: {
							value: "",
							edited: false,
						},
						description: {
							value: "",
							edited: false,
						},
						category: {
							value: "",
							edited: false,
						},
						degree: {
							value: "",
							edited: false,
						},
						rank: {
							value: "",
							edited: false,
						},
						startWorkAge: {
							value: "",
							edited: false,
						},
						startWorkCity: {
							value: "",
							edited: false,
						},
						adultDoctor: {
							value: "",
							edited: false,
						},
						childrenDoctor: {
							value: "",
							edited: false,
						},
						childrenDoctorAge: {
							value: "",
							edited: false,
						},
						hide: {
							value: false,
							edited: false,
						},
						filename: {
							value: "",
							edited: false,
						},
						path: {
							value: "",
							edited: false,
						},
					},
				},

				// Связи
				connections: {
					certificates: [],
					specializations: [],
					clinics: [],
					educations: [],
					works: [],
				},
			},

			/* Таблица */
			tableCertificates: {
				// Настройки
				options: {
					create: true,
					delete: true,
					update: true,
					report: false,
				},

				// Колонки
				head: [
					{ name: "id", text: "ID", columnType: "id" },
					{
						name: "name",
						text: "Название",
						columnType: "default",
						columnSize: "250px",
					},
					{
						name: "organization",
						text: "Организация",
						columnType: "default",
						columnSize: "auto",
					},
					{
						name: "endEducation",
						text: "Конец обучение",
						columnType: "default",
						columnSize: "250px",
					},
				],

				// Элементы
				body: [],
			},

			tableEducations: {
				// Настройки
				options: {
					create: true,
					delete: true,
					update: true,
					report: false,
				},

				// Колонки
				head: [
					{ name: "id", text: "ID", columnType: "id" },
					{
						name: "name",
						text: "Название",
						columnType: "default",
						columnSize: "250px",
					},
					{
						name: "organization",
						text: "Организация",
						columnType: "default",
						columnSize: "auto",
					},
					{
						name: "speсialization",
						text: "Специализация",
						columnType: "default",
						columnSize: "250px",
					},
				],

				// Элементы
				body: [],
			},

			tableWorks: {
				// Настройки
				options: {
					create: true,
					delete: true,
					update: true,
					report: false,
				},

				// Колонки
				head: [
					{ name: "id", text: "ID", columnType: "id" },
					{
						name: "name",
						text: "Название",
						columnType: "default",
						columnSize: "600px",
					},
					{
						name: "organization",
						text: "Организация",
						columnType: "default",
						columnSize: "auto",
					},
				],

				// Элементы
				body: [],
			},

			/* Секции */
			sections: {
				specializations: [],
				clinics: [],
			},

			/* Чекбоксовые массивы */
			cheked: {
				counter: 0,
				specializations: [],
				specializationsCategory: [],
				clinics: [],
			},

			/* Поисковые переменные */
			search: {
				specializations: "",
				clinics: "",
			},
		};
	},
	computed: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     ПРОФИЛЬ                       |*/
		/* |___________________________________________________|*/
		/* Получение отсортированных специализаций */
		sortedConnectionsSpecializations() {
			let specializations = [...this.specialist.connections.specializations];

			sorted.sortStringByKey("up", specializations, "name");

			return specializations;
		},

		getPagesSpecializationsTotal() {
			return Math.ceil(
				this.sections.specializations.length / this.paginationSpecializations.elements.range
			);
		},

		getSortedSpecializations() {
			let specializations = [...this.sections.specializations];

			specializations = specializations.filter((item) => {
				return item.name.toLowerCase().includes(this.search.specializations.toLowerCase());
			});

			sorted.sortStringByKey("up", specializations, "name");

			// return specializations.splice(
			// 	(this.paginationSpecializations.pages.current - 1) *
			// 		this.paginationSpecializations.elements.range,
			// 	this.paginationSpecializations.elements.range
			// );

			return specializations;
		},

		/* Клиники */
		getPagesClinicsTotal() {
			return Math.ceil(this.sections.clinics.length / this.paginationClinics.elements.range);
		},

		getSortedClinics() {
			let clinics = [...this.sections.clinics];

			clinics = clinics.filter((item) => {
				return item.name.toLowerCase().includes(this.search.clinics.toLowerCase());
			});

			sorted.sortStringByKey("up", clinics, "name");

			// return clinics.splice(
			// 	(this.paginationClinics.pages.current - 1) * this.paginationClinics.elements.range,
			// 	this.paginationClinics.elements.range
			// );

			return clinics;
		},
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			if (!this.loading.loader.profile) {
				for (let key in this.loading.sections) {
					this.loading.sections[key] = true;
				}
			}
		},

		/* Получить название сертификата */
		getCertificateName(id) {
			let certificate = this.sections.certificates.filter((item) => {
				if (item.id == id.id_certificate) {
					return item;
				}
			});
			return certificate[0].name;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		newOpenModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Специализации */
		openModalSpecializationsEdite() {
			// Обнуление выбранных специализаций
			this.cheked.specializations = [];

			// Заполнение выбранных специализаций
			this.specialist.connections.specializations.forEach((connSpecilization) =>
				this.cheked.specializations.push(connSpecilization.id_specialization)
			);

			this.newOpenModal("modalSpecializations", "СПЕЦИАЛИЗАЦИИ", "default");
		},

		/* Клиники */
		openModalClinicsEdite() {
			// Проверка на статус добавления специалиста
			if (this.specialist.profile.data.id.value === "new") return;

			// Обнуление массива
			this.cheked.clinics = [];

			// Заполнение выбранных клиник из массива
			this.specialist.connections.clinics.forEach((connClinic) => {
				this.cheked.clinics.push(connClinic.id_clinic);

				/* Заполнение статуса приёма в клиниках в массив */
				this.sections.clinics.find((clinic) => {
					return clinic.id == connClinic.id_clinic;
				}).priem = connClinic.priem;
			});

			this.newOpenModal("modalClinics", "КЛИНИКИ", "default");
		},

		/* Сертификаты */
		openModalCertificatesCreate() {
			shared.clearObjectFull(this.currentCertificate);

			this.newOpenModal("modalCertificates", "СЕРТИФИКАТ", "create");
		},

		openModalCertificatesEdite(value) {
			shared.clearObjectFull(this.currentCertificate);
			shared.setData(value, this.currentCertificate);

			this.newOpenModal("modalCertificates", "СЕРТИФИКАТ", "default");
		},

		/* Образования */
		openModalEducationsCreate() {
			shared.clearObjectFull(this.currentEducation);

			this.newOpenModal("modalEducations", "ОБРАЗОВАНИЕ", "create");
		},

		openModalEducationsEdite(value) {
			shared.clearObjectFull(this.currentEducation);
			shared.setData(value, this.currentEducation);

			this.newOpenModal("modalEducations", "ОБРАЗОВАНИЕ", "default");
		},

		/* Работы */
		openModalWorksCreate() {
			shared.clearObjectFull(this.currentWork);

			this.newOpenModal("modalWorks", "РАБОТА", "create");
		},

		openModalWorksEdite(value) {
			shared.clearObjectFull(this.currentWork);
			shared.setData(value, this.currentWork);

			this.newOpenModal("modalWorks", "РАБОТА", "default");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    ПАГИНАЦИЯ                      |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Изменение текущей страницы */
		changePageSpecializations(pageNumber) {
			// Проверка на превышение количества страниц
			if (
				pageNumber >
				Math.ceil(
					this.sections.specializations.length / this.paginationSpecializations.elements.range
				)
			) {
				return;
			} else if (pageNumber < 1) {
				return;
			}

			this.paginationSpecializations.pages.current = pageNumber;
		},

		/* Изменение текущей страницы */
		changePageClinics(pageNumber) {
			// Проверка на превышение количества страниц
			if (
				pageNumber >
				Math.ceil(this.sections.clinics.length / this.paginationClinics.elements.range)
			) {
				return;
			} else if (pageNumber < 1) {
				return;
			}

			this.paginationClinics.pages.current = pageNumber;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                       ВРАЧ                        |*/
		/* |___________________________________________________|*/
		/* Модульное сохранение данных */
		async saveSpecialistModular(type) {
			// Проверка на статус добавления специалиста
			if (this.specialist.profile.data.id.value === "new") return;

			switch (type) {
				case "all":
					this.saveSpecialistAll();
					break;
				default:
					break;
			}
		},

		/* Сохранение данных профиля */
		addSpecialist() {
			// Проверка на статус добавления специалиста
			if (this.specialist.profile.data.id.value !== "new") return;

			let errors = 0;

			if (
				validate.checkInputsAll(this.specialist.profile, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "link",
						type: "text",
					},
					{
						key: "family",
						type: "text",
					},
					{
						key: "category",
						type: "text",
					},
					{
						key: "adultDoctor",
						type: "boolean",
					},
					{
						key: "childrenDoctor",
						type: "boolean",
					},
					{
						key: "startWorkAge",
						type: "text",
					},
					{
						key: "startWorkCity",
						type: "text",
					},
					{
						key: "file",
						type: "file",
						value: this.$refs.fileUpload,
						formats: ["png", "webp"],
					},
				])
			)
				errors++;

			/* Если есть приём у детей */
			if (this.specialist.profile.data.childrenDoctor.value) {
				if (
					validate.checkInputsAll(this.specialist.profile, [
						{
							key: "childrenDoctorAge",
							type: "number",
						},
					])
				)
					errors++;
			}

			if (errors) return;

			let formData = new FormData();
			formData.append("image", this.$refs.fileUpload.files[0]);
			formData.append("formats", ["png", "webp"]);
			formData.append("profile", JSON.stringify(this.specialist.profile.data));

			this.disabled.profile.create = true;

			// Сохранение данных
			api({
				method: "post",
				url: this.$store.getters.urlApi + `add-specialist`,
				headers: {
					Accept: "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (!response) return;

					shared.clearObjectSelective(this.specialist.profile, "data", ["edited"]);
					this.$refs.fileUpload.value = null;

					//
					this.disabled.profile.create = false;

					this.specialist.profile.data.id.value = response.data.result.id;

					this.specialist.profile.data.path.value = response.data.result.path;
					this.specialist.profile.data.filename.value = files.basename(
						response.data.result.path
					);

					this.$router.push(String(response.data.result.id));
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.profile.create = false;
				});
		},

		/* Сохранение всех данных */
		saveSpecialistAll() {
			if (
				validate.checkInputsAll(this.specialist.profile, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "link",
						type: "text",
					},
					{
						key: "family",
						type: "text",
					},
					{
						key: "category",
						type: "text",
					},
					{
						key: "adultDoctor",
						type: "boolean",
					},
					{
						key: "childrenDoctor",
						type: "boolean",
					},
					{
						key: "startWorkCity",
						type: "text",
					},
					{
						key: "startWorkAge",
						type: "text",
					},
				])
			)
				return;

			/* Если есть приём у детей */
			if (this.specialist.profile.data.childrenDoctor.value) {
				if (
					validate.checkInputsAll(this.specialist.profile, [
						{
							key: "childrenDoctorAge",
							type: "number",
						},
					])
				)
					return;
			}

			/* Если загружен файл */
			if (this.$refs.fileUpload.files[0]) {
				if (
					validate.checkInputsAll(this.specialist.profile, [
						{
							key: "file",
							type: "file",
							value: this.$refs.fileUpload,
							formats: ["png", "webp"],
						},
					])
				)
					return;
			}

			this.disabled.profile.save = true;

			let formData = new FormData();
			formData.append("type", "all");

			// Данные блока профиля
			formData.append("image", this.$refs.fileUpload.files[0]);
			formData.append("formats", ["png", "webp"]);
			formData.append("profile", JSON.stringify(this.specialist.profile.data));

			// Id специалиста
			formData.append("id", JSON.stringify(this.specialist.profile.data.id.value));

			// Данные блока специализаций
			formData.append(
				"specializations",
				JSON.stringify(this.specialist.connections.specializations)
			);

			formData.append("clinics", JSON.stringify(this.specialist.connections.clinics));
			formData.append("certificates", JSON.stringify(this.tableCertificates.body));
			formData.append("educations", JSON.stringify(this.tableEducations.body));
			formData.append("works", JSON.stringify(this.tableWorks.body));

			// Сохранение данных
			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-specialist-modular`,
				headers: {
					Accept: "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (!response) return;

					shared.clearObjectSelective(this.specialist.profile, "data", ["edited"]);

					// Замена изображения профиля
					if (response.data.result.imagePath != null) {
						shared.clearObjectSelective(this.specialist.profile, "data", ["edited"]);
						this.$refs.fileUpload.value = "";

						this.specialist.profile.data.path.value = response.data.result.imagePath;
						this.specialist.profile.data.filename.value = files.basename(
							response.data.result.imagePath
						);
					}

					shared.updateId(this.tableCertificates.body, response.data.result.certificates);
					shared.clearDeletes(this.tableCertificates.body);
					shared.clearFlags(this.tableCertificates.body);

					shared.updateId(this.tableEducations.body, response.data.result.educations);
					shared.clearDeletes(this.tableEducations.body);
					shared.clearFlags(this.tableEducations.body);

					shared.updateId(this.tableWorks.body, response.data.result.works);
					shared.clearDeletes(this.tableWorks.body);
					shared.clearFlags(this.tableWorks.body);
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.profile.save = false;
				});
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                  СПЕЦИАЛИЗАЦИИ                    |*/
		/* |___________________________________________________|*/
		/* Обновление специализаций */
		updateSpecialization() {
			try {
				// Обнуление сертификатов специалиста
				this.specialist.connections.specializations = [];

				let maxId = 0;
				// Если ничего не выбрано, то оставляем пустой массив, иначе заполняем массив выбранными значениями
				if (this.cheked.specializations.length !== 0) {
					this.cheked.specializations.sort((a, b) => {
						if (a > b) {
							return 1;
						} else if (a < b) {
							return -1;
						} else {
							return 0;
						}
					});

					/* Заполнение выбранных специализаций в массив */
					this.cheked.specializations.forEach((item) => {
						this.specialist.connections.specializations.push({
							id: maxId + 1,
							id_specialist:
								this.$route.params.id !== "new"
									? this.specialist.profile.data.id.value
									: "new",
							id_specialization: item,
						});

						/* Поиск максимального id */
						this.specialist.connections.specializations.filter((item) => {
							if (item.id > maxId) maxId++;
						});
					});
				}

				this.$refs.modalSpecializations.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     КЛИНИКИ                       |*/
		/* |___________________________________________________|*/
		/* Обновление клиник */
		updateClinics() {
			try {
				// Обнуление сертификатов специалиста
				this.specialist.connections.clinics = [];

				let maxId = 0;
				// Если ничего не выбрано, то оставляем пустой массив, иначе заполняем массив выбранными значениями
				if (this.cheked.clinics.length !== 0) {
					this.cheked.clinics.sort((a, b) => {
						if (a > b) {
							return 1;
						} else if (a < b) {
							return -1;
						} else {
							return 0;
						}
					});

					// Заполнение выбранных специализаций в массив
					this.cheked.clinics.forEach((checkClinic) => {
						this.specialist.connections.clinics.push({
							id: maxId + 1,
							priem: this.sections.clinics.find((clinic) => {
								return clinic.id == checkClinic;
							}).priem,
							id_specialist:
								this.$route.params.id !== "new"
									? this.specialist.profile.data.id.value
									: "new",
							id_clinic: checkClinic,
						});

						// Поиск максимального id
						this.specialist.connections.clinics.filter((connClinic) => {
							if (connClinic.id > maxId) maxId++;
						});
					});
				}

				this.$refs.modalClinics.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   СЕРТИФИКАТЫ                     |*/
		/* |___________________________________________________|*/
		/* Добавление данных */
		addCertificate() {
			if (
				validate.checkInputsAll(this.currentCertificate, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "organization",
						type: "text",
					},
					{
						key: "endEducation",
						type: "text",
					},
				])
			)
				return;

			try {
				this.tableCertificates.body.push({
					id: shared.getMaxId(this.tableCertificates.body) + 1,
					name: this.currentCertificate.data.name.value,
					organization: this.currentCertificate.data.organization.value,
					endEducation: this.currentCertificate.data.endEducation.value,
					create: true,
					delete: false,
				});

				this.$refs.modalCertificates.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Обновление данных */
		updateCertificate() {
			if (
				validate.checkInputsAll(this.currentCertificate, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "organization",
						type: "text",
					},
					{
						key: "endEducation",
						type: "text",
					},
				])
			)
				return;

			try {
				let сertificateToUpdate = this.tableCertificates.body.find((сertificate) => {
					if (сertificate.id === this.currentCertificate.data.id.value) {
						return сertificate;
					}
				});

				for (let key in this.currentCertificate.data) {
					сertificateToUpdate[key] = this.currentCertificate.data[key].value;
				}

				this.$refs.modalCertificates.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ОБРАЗОВАНИЯ                     |*/
		/* |___________________________________________________|*/
		/* Добавление данных */
		addEducation() {
			if (
				validate.checkInputsAll(this.currentEducation, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "organization",
						type: "text",
					},
					{
						key: "date",
						type: "text",
					},
					{
						key: "speсialization",
						type: "text",
					},
				])
			)
				return;

			try {
				this.tableEducations.body.push({
					id: shared.getMaxId(this.tableEducations.body) + 1,
					name: this.currentEducation.data.name.value,
					organization: this.currentEducation.data.organization.value,
					date: this.currentEducation.data.date.value,
					speсialization: this.currentEducation.data.speсialization.value,
					create: true,
					delete: false,
				});

				this.$refs.modalEducations.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Обновление данных */
		updateEducation() {
			if (
				validate.checkInputsAll(this.currentEducation, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "organization",
						type: "text",
					},
					{
						key: "date",
						type: "text",
					},
					{
						key: "speсialization",
						type: "text",
					},
				])
			)
				return;

			try {
				let educationToUpdate = this.tableEducations.body.find((education) => {
					if (education.id === this.currentEducation.data.id.value) {
						return education;
					}
				});

				for (let key in this.currentEducation.data) {
					educationToUpdate[key] = this.currentEducation.data[key].value;
				}

				this.$refs.modalEducations.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 ПРОШЛЫЕ РАБОТЫ                    |*/
		/* |___________________________________________________|*/
		/* Добавление */
		addWork() {
			if (
				validate.checkInputsAll(this.currentWork, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "organization",
						type: "text",
					},
					{
						key: "startWork",
						type: "text",
					},
					{
						key: "endWork",
						type: "text",
					},
				])
			)
				return;

			try {
				this.tableWorks.body.push({
					id: shared.getMaxId(this.tableWorks.body) + 1,
					name: this.currentWork.data.name.value,
					organization: this.currentWork.data.organization.value,
					startWork: this.currentWork.data.startWork.value,
					endWork: this.currentWork.data.endWork.value,
					create: true,
					delete: false,
				});

				this.$refs.modalWorks.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Обновление */
		updateWork() {
			if (
				validate.checkInputsAll(this.currentWork, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "organization",
						type: "text",
					},
					{
						key: "startWork",
						type: "text",
					},
					{
						key: "endWork",
						type: "text",
					},
				])
			)
				return;

			try {
				let workToUpdate = this.tableWorks.body.find((work) => {
					if (work.id === this.currentWork.data.id.value) {
						return work;
					}
				});

				for (let key in this.currentWork.data) {
					workToUpdate[key] = this.currentWork.data[key].value;
				}

				this.$refs.modalWorks.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                      ОБЩЕЕ                        |*/
		/* |___________________________________________________|*/
		/* Получение возраста */
		getAge(age) {
			let ageDiff = new Date().getFullYear() - new Date(age).getFullYear();

			if (ageDiff) {
				return ageDiff;
			} else {
				return 0;
			}
		},

		/* Пометка на удаление */
		setFlagDelete(value) {
			value.delete = !value.delete;
		},

		/* Метод удаления значения из массива */
		removeArrValue(arrayName, value) {
			this.specialist.connections[arrayName] = this.specialist.connections[arrayName].filter(
				(item) => {
					if (item.id !== value.id) {
						return item;
					}
				}
			);
		},
	},
	mounted() {
		if (this.$route.params.id !== "new" && !Number.isNaN(Number(this.$route.params.id))) {
			api({
				method: "post",
				headers: {
					Accept: "application/json",
				},
				url: this.$store.getters.urlApi + `get-specialist-all`,
				data: {
					id: this.$route.params.id,
				},
			})
				.then((response) => {
					if (!response) return;

					// Заполнение профиля
					for (let key in response.data.result.specialist.profile) {
						this.specialist.profile.data[key].value =
							response.data.result.specialist.profile[key];
					}
					// Заполнение секций
					for (let key in response.data.result.sections) {
						this.sections[key] = response.data.result.sections[key];
					}

					// Заполнение соединений и таблиц
					for (let key in response.data.result.specialist.connections) {
						if (key === "certificates") {
							this.tableCertificates.body = response.data.result.specialist.connections[key];
							continue;
						}

						if (key === "educations") {
							this.tableEducations.body = response.data.result.specialist.connections[key];
							continue;
						}

						if (key === "works") {
							this.tableWorks.body = response.data.result.specialist.connections[key];
							continue;
						}

						this.specialist.connections[key] =
							response.data.result.specialist.connections[key];
					}

					// Добавление поля "Прием" в клиниках
					this.sections.clinics.forEach((item) => {
						item.priem = 0;

						this.specialist.connections.clinics.forEach((itemOther) => {
							if (itemOther.id_clinic == item.id) {
								item.priem = itemOther.priem;
							}
						});
					});

					// Добавление полей delete, create
					this.tableCertificates.body.forEach((item) => {
						item.create = false;
						item.delete = false;
					});

					this.tableEducations.body.forEach((item) => {
						item.create = false;
						item.delete = false;
					});

					this.tableWorks.body.forEach((item) => {
						item.create = false;
						item.delete = false;
					});

					// Сортировка специализаций и клиник по алфавиту
					const collator = new Intl.Collator("ru");
					this.sections.specializations.sort((a, b) => {
						return collator.compare(a.name, b.name);
					});
					this.sections.clinics.sort((a, b) => {
						return collator.compare(a.name, b.name);
					});
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					if (this.specialist.profile.data.id.value != "none") {
						for (let key in this.loading.loader) {
							this.loading.loader[key] = false;
						}
					}
				});
		} else if (this.$route.params.id == "new") {
			// Сброс данных всей полей профиля
			for (let key in this.specialist.profile.data) {
				switch (key) {
					case "id":
						this.specialist.profile.data[key].value = "new";
						break;
					case "path":
						this.specialist.profile.data[key].value = "storage/default/specialist.png";
						break;
					case "file":
						this.specialist.profile.data[key] = "";
						break;
					default:
						this.specialist.profile.data[key].value = "";
						break;
				}
			}

			// Сброс данных всех связей
			for (let key in this.specialist.connections) {
				this.specialist.connections[key] = [];
			}

			api({
				method: "post",
				url: this.$store.getters.urlApi + `get-specialist-sections`,
			})
				.then((response) => {
					if (!response) return;

					for (let key in response.data.result) {
						this.sections[key] = response.data.result[key];
					}

					// Добавление поля "Прием" в клиниках
					this.sections.clinics.forEach((item) => {
						item.priem = 0;

						this.specialist.connections.clinics.forEach((itemOther) => {
							if (itemOther.id_clinic == item.id) {
								item.priem = itemOther.priem;
							}
						});
					});
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					for (let key in this.loading.loader) {
						this.loading.loader[key] = false;
					}
				});
		} else {
			this.specialist.profile.data.id.value = null;

			this.$store.commit("addDebugger", {
				title: "Ошибка.",
				body: "Произошла непредвиденная ошибка.",
				type: "error",
			});
		}
	},
};
</script>

<style scoped>
.input-search {
	position: sticky;
	z-index: 1;

	top: 0px;
	right: 0px;
	padding-bottom: 20px;

	background: #ffffff;
	background: linear-gradient(0deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 30%);
}

/* Основной чекбокс */
.list__checkbox-item {
	cursor: pointer;

	position: relative;

	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 10px;

	border: var(--list-checkbox-border);
	border-radius: var(--list-checkbox-border-radius);
	padding: var(--list-checkbox-padding);

	color: var(--list-checkbox-font-color);

	font-size: 1.125rem;
	background-color: var(--list-checkbox-background-color);

	transition: all 0.2s ease;
}

.list__checkbox-item:hover {
	border: var(--input-border-focus);
	background-color: var(--item-background-color-active);
}

.list__checkbox-input {
	appearance: none;
	position: relative;

	border-radius: 7.5px;
	border: var(--default-border);

	width: 30px;
	height: 30px;

	transition: all 0.2s;
}

.list__checkbox-input:hover {
	border: var(--default-border-active);
	background-color: var(--default-background-color);
}

.list__checkbox-label {
	display: flex;
	gap: 10px;
}

.list__checkbox-icon {
	position: absolute;
	top: 12px;
	right: 10px;
	display: flex;
	width: 30px;
	height: 30px;
	padding: 3px;
	justify-content: center;
	align-items: center;
	border: 1px solid transparent;
	border-radius: 20px;
	user-select: none;
}

.list__checkbox-icon--checked,
.list__checkbox-input:checked + .list__checkbox-icon > .list__checkbox-icon--unchecked {
	display: none;
}

.list__checkbox-input:checked {
	border: var(--default-border-active);
	background-color: var(--default-background-color);
}

.list__checkbox-input:checked + .list__checkbox-icon,
.radio-input:checked + .radio-new {
	color: #000000;
}

.list__checkbox-input:checked + .list__checkbox-icon > .list__checkbox-icon--checked,
.list__checkbox-input:not(:checked) + .list__checkbox-icon > .list__checkbox-icon--unchecked {
	display: initial;
	fill: var(--primary-color);
}

.list__checkbox-input:disabled {
	background: gray;
}

:is(.specializations-list, .clinics__list) {
	display: flex;
	flex-direction: column;
	gap: 10px;

	margin-top: -10px;
}

.specializations-list {
	min-height: 678px;
}

.clinics__list {
	min-height: 567px;
}

:is(.specializations-list, .clinics__list) > .clinics__list-head {
	cursor: pointer;
	justify-content: center;
	align-items: center;

	display: grid;
	gap: 10px;
	border: var(--input-border);
	border-radius: calc(var(--default-border-radius) / 1.5);
	padding: 15px;

	font-size: 18px;

	transition: all 0.2s;
}

.clinics__list-item {
	display: flex;
	flex-direction: column;
	gap: 10px;
	justify-content: space-between;

	border: var(--input-border);
	border-radius: calc(var(--default-border-radius) / 1.5);
	padding: 15px;

	transition: all 0.2s;
}

.clinics__list-item:hover {
	border: var(--input-border-focus);
	background-color: var(--item-background-color-active);
}

.clinics__list-item > .list__checkbox-item {
	border: 0px;
	padding: 5px 0px;

	background-color: rgba(0, 0, 0, 0);
}

.clinics__list-item > .list__checkbox-item:hover {
	border: 0px;
	background-color: rgba(0, 0, 0, 0);
}

.clinics__list-item .list__checkbox-icon {
	position: absolute;
	top: 7px;
	right: 0px;
	display: flex;
	width: 30px;
	height: 30px;
	padding: 3px;
	justify-content: center;
	align-items: center;
	border: 1px solid transparent;
	border-radius: 20px;
	user-select: none;
}

.clinics__list-item select {
	padding: 10px;
	border: var(--input-border);
	border-radius: var(--input-border-radius);

	background-color: white;
	font-size: 18px;
	outline: none;
}

.specializations-list > .clinics__list-head {
	grid-template-columns: 30px 30px 1fr;
}

.clinics__list > .clinics__list-head {
	grid-template-columns: 30px 30px 1fr 1fr;
}

:is(.specializations-list, .clinics__list) > .clinics__list-head > select {
	border: 0px;
	background-color: rgba(0, 0, 0, 0);
	font-size: 18px;
	outline: none;
}

:is(.specializations-list, .clinics__list) > .clinics__list-head.active {
	border: var(--input-border-focus);
	background-color: var(--item-background-color-active);
}

/* Конейнер профиля */
.container-profile {
	display: flex;
	flex-wrap: wrap;
	gap: 20px;

	animation: show-bottom-to-top-15 0.4s ease-in-out;
}

.container-profile-other {
	display: flex;
	flex-direction: column;
	gap: 20px;

	animation: show-bottom-to-top-15 0.4s ease-in-out;
}

.profile-image {
	flex: 1 0 500px;
	display: flex;
	justify-content: center;
	align-items: center;
	min-height: 300px;
}

.profile-image > .clinics__list-head {
	width: 500px;
	height: 100%;
	background-size: contain;
	background-repeat: no-repeat;
	background-position: center;
}

.profile-info {
	flex: 1 0 500px;
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.profile-list {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	gap: 10px;

	animation: show-bottom-to-top-15 0.4s ease-in-out;
}

.profile-list > .clinics__list-head {
	display: grid;
	align-items: center;
	grid-template-columns: 1fr 1fr 30px;
	gap: 10px;

	border: var(--input-border);
	border-radius: calc(var(--default-border-radius) / 1.5);
	padding: 10px;

	font-size: 18px;

	transition: all 0.2s;
}

.profile-list > .clinics__list-head:first-of-type {
	display: grid;
	align-items: center;
	grid-template-columns: 1fr 1fr 30px;
	gap: 10px;

	border: 0px;
	border-radius: calc(var(--default-border-radius) / 1.5);
	padding: 0px 10px;

	color: var(--input-border-color-active);
	font-size: 18px;

	transition: all 0.2s;
}

.profile-list > .item-empty {
	display: flex;
	justify-content: center;
	align-items: center;
	height: 100%;
	color: #bcbcbc;
}

.profile-list > .clinics__list-head > .item-close {
	display: flex;
	justify-content: center;
	align-items: center;

	justify-self: end;
	align-self: end;
}

@media screen and (width <= 1000px) {
	.profile-info {
		flex: 1 0 0px;
	}
}

@media screen and (width <= 600px) {
	.container-profile {
		flex-direction: column;
	}

	.profile-image {
		flex: 1 0 0px;
	}

	.profile-image > .clinics__list-head {
		width: 100%;
	}
}
</style>
