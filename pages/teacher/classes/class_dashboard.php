<?php
session_start();

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "teacher"){
    header("Location: ../../../index.php");
    exit();
}

include '../../../includes/db.php'; 

/* Comprobamos que el ID venga por la URL */
if (!isset($_GET['id_class'])) {
    header("Location: classes.php");
    exit();
}

try {
    /*Barrera de seguridad, solo el profe dueño de la clase puede acceder a la modificación de esta*/
    $id_class = $_GET['id_class'];

    $id = $_SESSION['user_id'];

    $sql = "SELECT * FROM Class WHERE id_teacher = :id AND id_class = :id_class"; 
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([':id' => $id, ':id_class' => $id_class]); 
    
    $classes = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$classes){
        header("Location: classes.php");
        exit();
    }

    /* Buscamos los temas creados dentro de la clase*/
    $sql_topics = "SELECT * FROM Topic WHERE id_class = :id_class ORDER BY number ASC";

    $stmt_topics = $pdo->prepare($sql_topics);
    
    $stmt_topics->execute([':id_class' => $id_class]);
    
    $topics = $stmt_topics->fetchAll(PDO::FETCH_ASSOC);


    
}
catch (PDOException $e) {
    $error = "Error al cargar la clase: " . $e->getMessage();
}

include '../../../includes/header.php';

?>
<main>
    <div class="header-section">
        <h1>Temario: <?php echo htmlspecialchars($classes['material']); ?> Curso: <?php echo htmlspecialchars($classes['course']); ?></h1>
        
        <a href="topic/create_topic.php?id_class=<?php echo $id_class; ?>">
            <button>+ Nuevo Tema</button>
        </a>
        <a href="classes.php">Volver</a>
    </div>

    <section class="topics-list">
        <?php if (count($topics) > 0): ?>
            <?php foreach ($topics as $topic): ?>
                
                <div class="topic-card">
                    <a href="topic/topic_details.php?id_topic=<?php echo $topic['id_topic']; ?>&id_class=<?php echo $_GET['id_class']; ?>" class="topic-link">
                        <div class="topic-content">
                            <h3>
                                UT<?php echo $topic['number']; ?> - <?php echo htmlspecialchars($topic['title']); ?>
                            </h3>
                            <?php if(!empty($topic['subtitle'])): ?>
                                <p class="subtitle"><?php echo htmlspecialchars($topic['subtitle']); ?></p>
                            <?php endif; ?>
                            
                            <div class="topic-info">
                                <span>Actividades: </span>
                            </div>
                        </div>
                        <div class="topic-arrow">
                            <i>→</i>
                        </div>
                    </a>
                    <a href="topic/modify_topic.php?id=<?php echo $topic['id_topic']; ?>&id_class=<?php echo $_GET['id_class']; ?>"><button>Modificar</button></a>
                    <a href="topic/dell_topic.php?id=<?php echo $topic['id_topic']; ?>&id_class=<?php echo $_GET['id_class']; ?>"><button style="color: red;">Eliminar</button></a>
                </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p>Aún no se han creado temas para esta asignatura.</p>
        <?php endif; ?>
    </section>
</main>

<?php include '../../../includes/footer.php'; ?>