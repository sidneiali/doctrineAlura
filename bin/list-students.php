<?php

use Alura\Doctrine\Entity\Course;
use Alura\Doctrine\Entity\Phone;
use Alura\Doctrine\Entity\Student;
use Alura\Doctrine\Helper\EntityManagerCreator;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManager = EntityManagerCreator::createEntityManager();

$studentClass = Student::class;
$studentRepository = $entityManager->getRepository(Student::class);
$studentList = $studentRepository->studentsAndCourses();

foreach ($studentList as $student) {
    echo "ID: $student->id\nNome: $student->name";

    if ($student->phones()->count() > 0) {
        echo PHP_EOL;
        echo "Telefone:\n";
        echo implode(', ', $student->phones()
            ->map(fn(Phone $phone) => $phone->number)
            ->toArray());
    }

    if ($student->courses()->count() > 0) {
        echo PHP_EOL;
        echo "Cursos:\n";
        echo implode(', ', $student->courses()
            ->map(fn(Course $course) => $course->nome)
            ->toArray());
    }

    echo PHP_EOL . PHP_EOL;
}

$dql = "SELECT COUNT(student) FROM $studentClass student";
$query = $entityManager->createQuery($dql)->enableResultCache(86400);
echo $query->getSingleScalarResult() . PHP_EOL;
