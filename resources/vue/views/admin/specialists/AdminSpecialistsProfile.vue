<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<!--____________________________________________________-->
	<!--1. Специализации                                    -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<admin-modal @touchCloseModal="closeModal('modalSpecializations')" :modal="modalSpecializations">
		<template #title>
			<span class="create" v-if="modalSpecializations.type == 'create'">
				СПЕЦИАЛИЗАЦИЯ (СОЗДАНИЕ)
			</span>
			<span v-if="modalSpecializations.type == 'edit'">СПЕЦИАЛИЗАЦИИ</span>
		</template>
		<template #body>
			<!-- Список специализаций -->
			<div class="specializations-list">
				<label class="item">
					<div></div>
					<div></div>
					<div>Название</div>
				</label>
				<label
					class="item"
					v-for="(specialization, index) in getSortedSpecializations"
					:class="{ active: cheked.specializations.includes(specialization.id) }"
				>
					<div>
						#{{
							index +
							1 +
							paginationSpecializations.elements.range *
								(paginationSpecializations.pages.current - 1)
						}}
					</div>
					<input type="checkbox" :value="specialization.id" v-model="cheked.specializations" />
					<div>{{ specialization.name }}</div>
				</label>
			</div>

			<!-- Пагинация -->
			<pagination
				v-if="sections.specializations.length > paginationSpecializations.elements.range"
				:arrayLength="sections.specializations.length"
				:settings="paginationSpecializations"
				@changePage="changePageSpecializations"
			/>
		</template>
		<template #footer>
			<block-buttons>
				<button-default @click="updateSpecialization">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
			</block-buttons>
		</template>
	</admin-modal>
	<!--____________________________________________________-->
	<!--2. Клиники                                          -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<admin-modal @touchCloseModal="closeModal('modalClinics')" :modal="modalClinics">
		<template #title>
			<span class="create" v-if="modalClinics.type == 'create'"> СПЕЦИАЛИЗАЦИЯ (СОЗДАНИЕ) </span>
			<span v-if="modalClinics.type == 'edit'">КЛИНИКИ</span>
		</template>
		<template #body>
			<!-- Список специализаций -->
			<div class="clinics-list">
				<label class="item">
					<div></div>
					<div></div>
					<div>Название</div>
					<div>Статус приёма</div>
				</label>
				<label
					class="item"
					v-for="(clinic, index) in getSortedClinics"
					:key="clinic.id"
					:class="{ active: cheked.clinics.includes(clinic.id) }"
				>
					<div>
						#{{
							index +
							1 +
							paginationClinics.elements.range * (paginationClinics.pages.current - 1)
						}}
					</div>
					<input
						type="checkbox"
						:id="`clin-${clinic.id}`"
						:value="clinic.id"
						v-model="cheked.clinics"
					/>
					<div>{{ clinic.name }}</div>
					<select v-model="clinic.priem">
						<option :value="0">Нет</option>
						<option :value="1">Да</option>
					</select>
				</label>
			</div>
			<!-- Пагинация -->
			<pagination
				v-if="sections.clinics.length > paginationClinics.elements.range"
				:arrayLength="sections.clinics.length"
				:settings="paginationClinics"
				@changePage="changePageClinics"
			/>
		</template>
		<template #footer>
			<block-buttons>
				<button-default @click="updateClinics">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
			</block-buttons>
		</template>
	</admin-modal>
	<!--____________________________________________________-->
	<!--3. Сертификаты                                      -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<admin-modal @touchCloseModal="closeModal('modalCertificates')" :modal="modalCertificates">
		<template #title>
			{{ modalCertificates.title }}
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
			<block-buttons>
				<button-default @click="addCertificate" v-if="modalCertificates.type == 'create'">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
				<button-default @click="updateCertificate" v-if="modalCertificates.type == 'edit'">
					<Icon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</button-default>
			</block-buttons>
		</template>
	</admin-modal>

	<!--____________________________________________________-->
	<!--4. Образования                                      -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<admin-modal @touchCloseModal="closeModal('modalEducations')" :modal="modalEducations">
		<template #title>
			{{ modalEducations.title }}
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
						<span :class="{ create: modalEducations.type == 'create' }">ОРГАНИЗАЦИЯ*</span>
						<span
							:class="{ create: modalEducations.type == 'create' }"
							v-if="currentEducation.data.organization.edited"
						>
							(ИЗМЕНЕНО)
						</span>
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
						<span :class="{ create: modalEducations.type == 'create' }">ДАТА ПОЛУЧЕНИЯ*</span>
						<span
							:class="{ create: modalEducations.type == 'create' }"
							v-if="currentEducation.data.date.edited"
						>
							(ИЗМЕНЕНО)</span
						>
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
						<span :class="{ create: modalEducations.type == 'create' }">СПЕЦИАЛИЗАЦИЯ*</span>
						<span
							:class="{ create: modalEducations.type == 'create' }"
							v-if="currentEducation.data.speсialization.edited"
						>
							(ИЗМЕНЕНО)</span
						>
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
			<block-buttons>
				<button-default @click="addEducation" v-if="modalEducations.type == 'create'">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
				<button-default @click="updateEducation" v-if="modalEducations.type == 'edit'">
					<Icon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</button-default>
			</block-buttons>
		</template>
	</admin-modal>

	<!--____________________________________________________-->
	<!--5. Прошлые работы                                   -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<admin-modal @touchCloseModal="closeModal('modalWorks')" :modal="modalWorks">
		<template #title>
			{{ modalWorks.title }}
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
			<block-buttons>
				<button-default @click="addWork" v-if="modalWorks.type == 'create'">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
				<button-default @click="updateWork" v-if="modalWorks.type == 'edit'">
					<Icon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</button-default>
			</block-buttons>
		</template>
	</admin-modal>

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
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
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
						class="item"
						:style="{
							backgroundImage: `url(/storage/default/image-none-default.png)`,
						}"
					></div>
				</div>
				<div class="profile-image" v-else>
					<div
						class="item"
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
							<span>НАЧАЛО КАРЪЕРЫ</span>
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
								v-model="specialist.profile.data.startWorkCity.value"
								@input="specialist.profile.data.startWorkCity.edited = true"
							/>
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
						<div class="item">
							<div>Название</div>
						</div>
						<div class="item" v-for="specialization in sortedConnectionsSpecializations">
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
				<button-disabled v-if="this.specialist.profile.data.id.value == null">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-disabled>

				<button-default
					@click="editSpecialization"
					v-if="$route.params.id !== 'new' && this.specialist.profile.data.id.value !== null"
				>
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
				<button-claim @click="editSpecialization" v-if="$route.params.id === 'new'">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-claim>
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
						<div class="item">
							<div>Название</div>
							<div>Приём</div>
							<div></div>
						</div>
						<!-- Если клиники выбраны -->
						<div
							class="item"
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
				<button-disabled v-if="this.specialist.profile.data.id.value == null">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-disabled>

				<button-default
					@click="editClinics"
					v-if="$route.params.id !== 'new' && this.specialist.profile.data.id.value !== null"
				>
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
					@create="editArrayValue('create', 'certificates', null)"
					@edite="editArrayValue('edit', 'certificates', $event)"
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
					@create="editArrayValue('create', 'educations', null)"
					@edite="editArrayValue('edit', 'educations', $event)"
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
					@create="editArrayValue('create', 'works', null)"
					@edite="editArrayValue('edit', 'works', $event)"
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
import AdminModal from "../../../components/includes/admin/AdminModal.vue";
import BaseTable from "../../../components/modules/table/BaseTable.vue";
import Tiptap from "../../../components/modules/Tiptap.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import Selector from "../../../components/modules/Selector.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/elements/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTwo from "../../../components/ui/admin/blocks/BlockTwo.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";
import SpanError from "../../../components/ui/admin/SpanError.vue";

import TableButtonDefault from "../../../components/ui/admin/tables/TableButtonDefault.vue";
import TableButtonRemove from "../../../components/ui/admin/tables/TableButtonRemove.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/containers/input/ContainerInputTwo.vue";
import ContainerInputTwoSub from "../../../components/ui/admin/containers/input/ContainerInputTwoSub.vue";
import ContainerInputThree from "../../../components/ui/admin/containers/input/ContainerInputThree.vue";
import ContainerSelectOnce from "../../../components/ui/admin/containers/select/ContainerSelectOnce.vue";
import ContainerSelectThree from "../../../components/ui/admin/containers/select/ContainerSelectThree.vue";
import ContainerTextareaOnce from "../../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

import Pagination from "../../../components/ui/admin/pagination/Pagination.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonDisabled from "../../../components/ui/admin/buttons/ButtonDisabled.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import Icon from "../../../components/modules/icon/Icon.vue";
import IconClose from "../../../components/icons/IconClose.vue";

import axios from "axios";

import { RouterView, RouterLink } from "vue-router";
import validate from "../../../services/validate";
import files from "../../../services/files";
import shared from "../../../services/shared";
import sorted from "../../../services/sorted";

export default {
	components: {
		AdminModal,
		BaseTable,
		Tiptap,
		InfoBar,
		Selector,
		LoaderChild,
		ElementInputLabel,

		BlockOnce,
		BlockTwo,
		BlockButtons,

		SpanError,

		ContainerInput,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputTwoSub,
		ContainerInputThree,
		ContainerSelectOnce,
		ContainerSelectThree,
		ContainerTextareaOnce,

		Pagination,
		TableButtonDefault,
		TableButtonRemove,

		ButtonDefault,
		ButtonDisabled,
		ButtonRemove,
		ButtonClaim,

		Icon,
		IconClose,

		axios,
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
				title: "",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},

			modalClinics: {
				title: "",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},

			modalCertificates: {
				title: "СЕРТИФИКАТ",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},

			modalEducations: {
				title: "ОБУЧЕНИЕ",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},

			modalWorks: {
				title: "РАБОТА",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
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
					current: 1,
					range: 5,
				},
				elements: {
					range: 10,
				},
			},

			paginationClinics: {
				pages: {
					current: 1,
					range: 5,
				},
				elements: {
					range: 10,
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

			sorted.sortStringByKey("up", specializations, "name");

			return specializations.splice(
				(this.paginationSpecializations.pages.current - 1) *
					this.paginationSpecializations.elements.range,
				this.paginationSpecializations.elements.range
			);
		},

		/* Клиники */
		getPagesClinicsTotal() {
			return Math.ceil(this.sections.clinics.length / this.paginationClinics.elements.range);
		},

		getSortedClinics() {
			let clinics = [...this.sections.clinics];

			sorted.sortStringByKey("up", clinics, "name");

			return clinics.splice(
				(this.paginationClinics.pages.current - 1) * this.paginationClinics.elements.range,
				this.paginationClinics.elements.range
			);
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
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие */
		openModal(type, modalName, currentName) {
			if (modalName !== "modalSpecializations" && modalName !== "modalClinics") {
				shared.clearObjectSelective(this[currentName], "errors", ["status", "message"]);
				shared.clearObjectSelective(this[currentName], "data", ["edited"]);
			}

			switch (type) {
				case "create":
					{
						if (modalName !== "modalSpecializations" && modalName !== "modalClinics") {
							shared.clearObjectSelective(this[currentName], "data", ["value", "edited"]);
						}

						this[modalName].type = "create";
						this[modalName].status = true;
					}
					document.body.classList.add("modal-open");
					break;
				case "edit":
					{
						this[modalName].type = "edit";
						this[modalName].status = true;
					}
					document.body.classList.add("modal-open");
					break;
				default:
					{
						let debbugStory = {
							title: "Ошибка.",
							body: "Низвестный тип открытия модального окна.",
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
					break;
			}
		},
		/* Закрытие */
		closeModal(modalName) {
			this[modalName].status = false;
			document.body.classList.remove("modal-open");
		},

		/*  */
		getAge(age) {
			let ageDiff = new Date().getFullYear() - new Date(age).getFullYear();

			if (ageDiff) {
				return ageDiff;
			} else {
				return 0;
			}
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
			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `add-specialist`,
				headers: {
					Accept: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					shared.clearObjectSelective(this.specialist.profile, "data", ["edited"]);
					this.$refs.fileUpload.value = null;

					if (response.data.status) {
						this.disabled.profile.create = false;

						this.specialist.profile.data.id.value = response.data.data.id;

						this.specialist.profile.data.path.value = response.data.data.path;
						this.specialist.profile.data.filename.value = files.basename(
							response.data.data.path
						);

						this.$router.push(String(response.data.data.id));

						this.$store.commit("addDebugger", {
							title: "Успешно!",
							body: response.data.message,
							type: "completed",
						});
					} else {
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: response.data.message,
							type: "error",
						});
					}
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
			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-specialist-modular`,
				headers: {
					Accept: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						shared.clearObjectSelective(this.specialist.profile, "data", ["edited"]);

						// Замена изображения профиля
						if (response.data.data.imagePath != null) {
							shared.clearObjectSelective(this.specialist.profile, "data", ["edited"]);
							this.$refs.fileUpload.value = "";

							this.specialist.profile.data.path.value = response.data.data.imagePath;
							this.specialist.profile.data.filename.value = files.basename(
								response.data.data.imagePath
							);
						}

						shared.updateId(this.tableCertificates.body, response.data.data.certificates);
						shared.clearDeletes(this.tableCertificates.body);
						shared.clearFlags(this.tableCertificates.body);

						shared.updateId(this.tableEducations.body, response.data.data.educations);
						shared.clearDeletes(this.tableEducations.body);
						shared.clearFlags(this.tableEducations.body);

						shared.updateId(this.tableWorks.body, response.data.data.works);
						shared.clearDeletes(this.tableWorks.body);
						shared.clearFlags(this.tableWorks.body);

						this.$store.commit("addDebugger", {
							title: "Успешно!",
							body: response.data.message,
							type: "completed",
						});
					} else {
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: response.data.message,
							type: "error",
						});
					}
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

		/* _____________________________________________________*/
		/* 2. Специализации                                     */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие списка специализация */
		editSpecialization() {
			// Обнуление выбранных специализаций
			this.cheked.specializations = [];

			// Заполнение выбранных специализаций
			this.specialist.connections.specializations.forEach((connSpecilization) =>
				this.cheked.specializations.push(connSpecilization.id_specialization)
			);

			this.openModal("edit", "modalSpecializations", null);
		},
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
				this.closeModal("modalSpecializations");
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},
		/* _____________________________________________________*/
		/* 3. Клиники                                           */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие списка клиник */
		editClinics() {
			try {
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

				this.openModal("edit", "modalClinics", null);
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

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

				this.closeModal("modalClinics");
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* _____________________________________________________*/
		/* 4. Сертификаты                                       */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
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
				this.closeModal("modalCertificates");
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

				this.closeModal("modalCertificates");
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* _____________________________________________________*/
		/* 5. Образования                                       */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
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

				this.closeModal("modalEducations");
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

				this.closeModal("modalEducations");
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},
		/* _____________________________________________________*/
		/* 6. Прошлые работы                                    */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
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

				this.closeModal("modalWorks");
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

				this.closeModal("modalWorks");
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* _____________________________________________________*/
		/* Общие методы                                         */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
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

		/* Метод открытия модального окна для обновления значений */
		editArrayValue(type, arrayName, value) {
			switch (arrayName) {
				case "certificates":
					/* Создание */
					if (type == "create") {
						this.openModal(type, "modalCertificates", "currentCertificate");
					}
					/* Редактирование */
					if (type == "edit") {
						let filterCertificate = this.tableCertificates.body.find((item) => {
							if (item.id == value.id) {
								return item;
							}
						});

						for (let key in this.currentCertificate.data) {
							this.currentCertificate.data[key].value = filterCertificate[key];
						}

						this.openModal(type, "modalCertificates", "currentCertificate");
					}
					break;
				case "educations":
					/* Создание */
					if (type == "create") {
						this.openModal(type, "modalEducations", "currentEducation");
					}
					/* Редактирование */
					if (type == "edit") {
						let filterEducation = this.tableEducations.body.find((item) => {
							if (item.id == value.id) {
								return item;
							}
						});

						for (let key in this.currentEducation.data) {
							this.currentEducation.data[key].value = filterEducation[key];
						}

						this.openModal(type, "modalEducations", "currentEducation");
					}
					break;
				case "works":
					/* Создание */
					if (type == "create") {
						this.openModal(type, "modalWorks", "currentWork");
					}

					/* Редактирование */
					if (type == "edit") {
						let filterWorks = this.tableWorks.body.find((item) => {
							if (item.id == value.id) {
								return item;
							}
						});

						for (let key in this.currentWork.data) {
							this.currentWork.data[key].value = filterWorks[key];
						}

						this.openModal(type, "modalWorks", "currentWork");
					}

					break;
			}
		},
	},
	mounted() {
		if (this.$route.params.id !== "new" && !Number.isNaN(Number(this.$route.params.id))) {
			axios({
				method: "post",
				headers: {
					Accept: "application/json",
				},
				url: `${this.$store.getters.urlApi}` + `get-specialist-all`,
				data: {
					id: this.$route.params.id,
				},
			})
				.then((response) => {
					if (response.data.status) {
						// Заполнение профиля
						for (let key in response.data.data.specialist.profile) {
							this.specialist.profile.data[key].value =
								response.data.data.specialist.profile[key];
						}
						// Заполнение секций
						for (let key in response.data.data.sections) {
							this.sections[key] = response.data.data.sections[key];
						}

						// Заполнение соединений и таблиц
						for (let key in response.data.data.specialist.connections) {
							if (key === "certificates") {
								this.tableCertificates.body =
									response.data.data.specialist.connections[key];
								continue;
							}

							if (key === "educations") {
								this.tableEducations.body = response.data.data.specialist.connections[key];
								continue;
							}

							if (key === "works") {
								this.tableWorks.body = response.data.data.specialist.connections[key];
								continue;
							}

							this.specialist.connections[key] =
								response.data.data.specialist.connections[key];
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
					} else {
						this.specialist.profile.data.id.value = "none";

						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: response.data.message,
							type: "error",
						});
					}
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

			axios({
				method: "post",
				headers: {
					Accept: "application/json",
				},
				url: `${this.$store.getters.urlApi}` + `get-specialist-sections`,
			})
				.then((response) => {
					for (let key in response.data) {
						this.sections[key] = response.data[key];
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
					console.log(error);
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
.children-doctor {
	display: flex;
	gap: 10px;
}

.children-doctor > :is(input, select) {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: calc(var(--default-border-radius) / 1.5);

	width: 100%;
	height: 58px;

	font-size: 20px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

.children-doctor > :is(input:focus, select:focus) {
	border: 2px solid var(--input-border-color-active);
}

.children-doctor > input::placeholder {
	color: var(--input-placeholder-color);
}

.children-doctor > select {
	flex: 3 0 100px;
}

.children-doctor > input {
	flex: 1 0 50px;
}

:is(.specializations-list, .clinics-list) {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

:is(.specializations-list, .clinics-list) > .item {
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

.specializations-list > .item {
	grid-template-columns: 30px 30px 1fr;
}

.clinics-list > .item {
	grid-template-columns: 30px 30px 1fr 1fr;
}

:is(.specializations-list, .clinics-list) > .item > select {
	border: 0px;
	background-color: rgba(0, 0, 0, 0);
	font-size: 18px;
	outline: none;
}

.red {
	color: red;
}

:is(.specializations-list, .clinics-list) > .item.active {
	border: var(--input-border-focus);
	background-color: var(--item-background-color-active);
}

:is(.specializations-list, .clinics-list) > .item > input[type="checkbox"] {
	cursor: pointer;

	width: 17.5px;
	height: 17.5px;
}

:is(.specializations-list, .clinics-list) > .item > input[type="checkbox"]:checked {
	accent-color: var(--primary-color);
}

:is(.specializations-list, .clinics-list) > .item:first-of-type {
	display: grid;
	grid-template-columns: 40px 30px 1fr 1fr;
	gap: 10px;

	border: 0px;
	padding: 0px 10px;
	border-radius: calc(var(--default-border-radius) / 1.5);

	font-size: 18px;
	color: var(--input-border-color-active);
}

:is(.specializations-list, .clinics-list) > .item:not(:first-of-type):hover {
	border: var(--input-border-focus);
	background-color: var(--item-background-color-active);
}

:is(.specializations-list, .clinics-list) > .item > label {
	cursor: pointer;
	font-size: 18px;
}

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

.profile-image > .item {
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

.profile-list > .item {
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

.profile-list > .item:first-of-type {
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

.profile-list > .item > .item-close {
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

	.profile-image > .item {
		width: 100%;
	}
}
</style>
