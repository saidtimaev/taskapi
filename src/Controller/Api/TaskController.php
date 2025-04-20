<?php

namespace App\Controller\Api;

use App\Dto\TaskDto;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TaskController extends AbstractController{

    // Список задач
    #[Route('/api/tasks', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $tasks = $em->getRepository(Task::class)->findAll();

        $data = [];
        foreach ($tasks as $task) {
            $data[] = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'isDone' => $task->isDone(),
                'createdAt' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data);
    }

    // Добавление задачи
    #[Route('/api/task', methods: ['POST'])]
    public function addTask(Request $request, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        $request = json_decode($request->getContent(), true);

        $dto = new TaskDto();
        $dto->title = $data['title'] ?? '';
        $dto->description = $data['description'] ?? '';
        $dto->isDone = $data['isDone'] ?? false;

        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
    
            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $property = $error->getPropertyPath();
                $errorMessages[$property][] = $error->getMessage();
            }
    
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        $task = new Task();
        $task->setTitle($dto->title);
        $task->setDescription($dto->description);
        $task->setIsDone($dto->isDone);
        $task->setCreatedAt(new \DateTimeImmutable());

        $em->persist($task);
        $em->flush();

        return new JsonResponse(
            [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'isDone' => $task->isDone(),
                'createdAt' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
                'status' => 'Задача создана успешно'
            ], 
            201
        );
    }

    // Показать детали задачи
    #[Route('/api/task/{id}', methods: ['GET'])]
    public function getTaskDetails(int $id, EntityManagerInterface $em): JsonResponse
    {
        $task = $em->getRepository(Task::class)->find($id);

        if (!$task) {
            return $this->json(['message' => 'Задача не найдена'], 404);
        }

        return new JsonResponse(
            [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'isDone' => $task->isDone(),
                'createdAt' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
            ]
        );
    }
    
    // Частичное обновление задачи
    #[Route('/api/task/{id}', methods: ['PATCH'])]
    public function partiallyUpdateTask(int $id, Request $request, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $task = $em->getRepository(Task::class)->find($id);

        if (!$task) {
            return $this->json(['message' => 'Задача не найдена'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return $this->json(['message' => 'Invalid JSON'], 400);
        }

        $dto = new TaskDto();
        $dto->title = $data['title'] ?? '';
        $dto->description = $data['description'] ?? '';
        $dto->isDone = $data['isDone'] ?? false;

        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
    
            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $property = $error->getPropertyPath();
                $errorMessages[$property][] = $error->getMessage();
            }
    
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        $task->setTitle($dto->title);
        $task->setDescription($dto->description);
        $task->setIsDone($dto->isDone);

        $em->flush();

        return new JsonResponse(
            [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'isDone' => $task->isDone(),
                'createdAt' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
                'status' => 'Задача обновлена успешно'
            ]
        );
    }

    // Полное обновление задачи
    #[Route('/api/task/{id}', methods: ['PUT'])]
    public function fullyUpdateTask(int $id, Request $request, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $task = $em->getRepository(Task::class)->find($id);

        if (!$task) {
            return $this->json(['message' => 'Задача не найдена'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return $this->json(['message' => 'Invalid JSON'], 400);
        }

        $dto = new TaskDto();
        $dto->title = $data['title'] ?? '';
        $dto->description = $data['description'] ?? '';
        $dto->isDone = $data['isDone'] ?? false;

        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
    
            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $property = $error->getPropertyPath();
                $errorMessages[$property][] = $error->getMessage();
            }
    
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        $task->setTitle($dto->title);
        $task->setDescription($dto->description);
        $task->setIsDone($dto->isDone);

        $em->flush();

        return new JsonResponse(
            [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'isDone' => $task->isDone(),
                'createdAt' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
                'status' => 'Задача обновлена успешно'
            ]
        );
    }

    // Удаление задачи
    #[Route('/api/task/{id}', methods: ['DELETE'])]
    public function deleteTask(int $id, EntityManagerInterface $em): JsonResponse
    {
        $task = $em->getRepository(Task::class)->find($id);

        if (!$task) {
            return $this->json(['message' => 'Задача не найдена'], 404);
        }

        $em->remove($task);
        $em->flush();

        return new JsonResponse(['message' => 'Задача удалена успешно']);
    }
}
