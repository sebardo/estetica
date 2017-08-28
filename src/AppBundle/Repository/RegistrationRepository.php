<?php


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class RegistrationRepository extends EntityRepository
{
	public function findQueryByFilterForm($data)
	{
		$qb = $this->createQueryBuilder('r')
			->select('r')
			->orderBy('r.createdAt', 'DESC');

		//Name
		if(array_key_exists('name', $data) && !empty($data['name'])){
			$qb->andWhere('r.name LIKE :name')
				->setParameter('name', "%{$data['name']}%");
		}
		//Surname
		if(array_key_exists('surname', $data) && !empty($data['surname'])){
			$qb->andWhere('r.firstLastname LIKE :surname OR r.secondLastname LIKE :surname')
				->setParameter('surname', "%{$data['surname']}%");
		}
		//Email
		if(array_key_exists('email', $data) && !empty($data['email'])){
			$qb->andWhere('r.email LIKE :email')
				->setParameter('email', "%{$data['email']}%");
		}
		//Speciality
		if(array_key_exists('speciality', $data) && $data['speciality'] != -1){
			$qb->innerJoin('r.registrationsHaveSpecialities', 'rhs');
			$specialityCollection = $data['speciality'];
			foreach ($specialityCollection as $speciality) {
				$qb
					->andWhere('rhs.speciality = :specialityId')
					->setParameter('specialityId', $speciality);
			}
		}
		//Experience
		if(array_key_exists('experience', $data) && $data['experience'] != -1){
			$qb->innerJoin('r.experience', 'e')
				->andwhere('e.id = :experienceId')
				->setParameter('experienceId', $data['experience']);
		}
		//Vehicle
		if(array_key_exists('vehicle', $data) && !empty($data['vehicle'])){
			$qb->andWhere('r.vehicle = :vehicle')
				->setParameter('vehicle', $data['vehicle']);
		}
		//TravelAvailability
		if(array_key_exists('travelAvailability', $data) && !empty($data['travelAvailability'])){
			$qb->andWhere('r.travelAvailability = :travelAvailability')
				->setParameter('travelAvailability', $data['travelAvailability']);
		}
		//Language
		if(array_key_exists('language', $data) && !empty($data['language'])){
			$qb->innerJoin('r.registrationsHaveLanguages', 'rhl');
			$languageCollection = $data['language'];
			foreach ($languageCollection as $language) {
				$qb
					->andWhere('rhl.language = :languageId')
					->setParameter('languageId', $language);
			}
		}
		//TimeAvailability
		if(array_key_exists('timeAvailability', $data) && $data['timeAvailability'] != -1){
			$qb->innerJoin('r.timesAvailability', 'ta')
				->where('ta.id = :timeAvailabilityId')
				->setParameter('timeAvailabilityId', $data['timeAvailability']);
		}
		//CertificateDisability
		if(array_key_exists('certificateDisability', $data) && !empty($data['certificateDisability'])){
			$qb->andWhere('r.certificateDisability = :certificateDisability')
				->setParameter('certificateDisability', $data['certificateDisability']);
		}
		//SalesTraining
		if(array_key_exists('salesTraining', $data) && !empty($data['salesTraining'])){
			$qb->andWhere('r.salesTraining = :salesTraining')
				->setParameter('salesTraining', $data['salesTraining']);
		}
		//ContractType
		if(array_key_exists('contractType', $data) && $data['contractType'] != -1){
			$qb->innerJoin('r.contractTypes', 'ct')
				->andwhere('ct.id = :contractTypeId')
				->setParameter('contractTypeId', $data['contractType']);
		}
		//LevelResponsibility
		if(array_key_exists('levelResponsibility', $data) && $data['levelResponsibility'] != -1){
			$qb->innerJoin('r.levelsResponsibility', 'lr')
				->andwhere('lr.id = :levelResponsibilityId')
				->setParameter('levelResponsibilityId', $data['levelResponsibility']);
		}
		//Course
		if(array_key_exists('course', $data) && !empty($data['course'])){
			$qb->innerJoin('r.registrationsHaveCourses', 'rhc');
			$courseCollection = $data['course'];
			foreach ($courseCollection as $course) {
				$qb
					->andWhere('rhc.course = :courseId')
					->setParameter('courseId', $course);
			}
		}
		//Study
		if(array_key_exists('study', $data) && $data['study'] != -1){
			$qb->innerJoin('r.studies', 's')
				->andwhere('s.id = :studyId')
				->setParameter('studyId', $data['study']);
		}
		//AcademicStudy
		if(array_key_exists('academicStudy', $data) && $data['academicStudy'] != -1){
			$qb->innerJoin('r.academicStudies', 'as')
				->andwhere('as.id = :academicStudyId')
				->setParameter('academicStudyId', $data['academicStudy']);
		}
		//Country
		if(array_key_exists('country', $data) && !empty($data['country']) && empty($data['province']) && empty($data['city'])){
			$qb->innerJoin('r.placeResidence', 'pr');
			$qb->innerJoin('pr.city', 'city');
			$qb->innerJoin('city.province', 'province');
			$qb->innerJoin('province.country', 'country');
			$qb->andwhere('country.id = :countryId')
				->setParameter('countryId', $data['country']);
		}
		//Province
		if(array_key_exists('province', $data) && !empty($data['province']) && empty($data['city'])){
			$qb->innerJoin('r.placeResidence', 'pr');
			$qb->innerJoin('pr.city', 'city');
			$qb->innerJoin('city.province', 'province');
			$qb->andwhere('province.id = :provinceId')
				->setParameter('provinceId', $data['province']);
		}
		//City
		if(array_key_exists('city', $data) && !empty($data['city'])){
			$qb->innerJoin('r.placeResidence', 'pr');
			$qb->innerJoin('pr.city', 'city');
			$qb->andwhere('city.id = :cityId')
				->setParameter('cityId', $data['city']);
		}
		//PostalCode
		if(array_key_exists('postalCode', $data) && !empty($data['postalCode'])){
			$qb->innerJoin('r.placeResidence', 'pr');
			$qb->andWhere('pr.postalCode LIKE :postalCode')
				->setParameter('postalCode', "%{$data['postalCode']}%");
		}

		return $qb->getQuery();
	}
}