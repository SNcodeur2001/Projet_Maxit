<?php
    namespace App\Entity;
use App\Core\Abstract\AbstractEntity;

class Utilisateur extends AbstractEntity
{

    private int $id;
    private string $nom;
    private string $prenom;
    private ?string $adresse;
    private string $telephone;
    private ?string $numeroPieceIdentite;
    private ?string $photoRecto;
    private ?string $photoVerso;
    private ProfilEnum $profil;
    private ?array $comptes;

    public function __construct(
        string $nom,
        string $prenom,
        string $telephone,
        ProfilEnum $profil,
        ?string $adresse = null,
        ?string $numeroPieceIdentite = null,
        ?string $photoRecto = null,
        ?string $photoVerso = null,
        ?array $comptes = null
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->telephone = $telephone;
        $this->profil = $profil;
        $this->adresse = $adresse;
        $this->numeroPieceIdentite = $numeroPieceIdentite;
        $this->photoRecto = $photoRecto;
        $this->photoVerso = $photoVerso;
        $this->comptes = $comptes ?? []; // Initialise un tableau vide si null

        // Validation optionnelle du téléphone (exemple)
      
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getNom(): string
    {
        return $this->nom;
    }
    public function getPrenom(): string
    {
        return $this->prenom;
    }
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }
    public function getTelephone(): string
    {
        return $this->telephone;
    }
    public function getNumeroPieceIdentite(): ?string
    {
        return $this->numeroPieceIdentite;
    }
    public function getPhotoRecto(): ?string
    {
        return $this->photoRecto;
    }
    public function getPhotoVerso(): ?string
    {
        return $this->photoVerso;
    }
    public function getProfil(): ProfilEnum
    {
        return $this->profil;
    }
    public function getComptes(): array
    {
        return $this->comptes;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }
    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }
    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }
    public function setNumeroPieceIdentite(?string $numeroPieceIdentite): void
    {
        $this->numeroPieceIdentite = $numeroPieceIdentite;
    }
    public function setPhotoRecto(?string $photoRecto): void
    {
        $this->photoRecto = $photoRecto;
    }
    public function setPhotoVerso(?string $photoVerso): void
    {
        $this->photoVerso = $photoVerso;
    }
    public function setProfil(ProfilEnum $profil): void
    {
        $this->profil = $profil;
    }
    public function addCompte(Compte $compte): void
    {
        $this->comptes[] = $compte;
    }

         public static function toObject(array $tableau): static{
          return new static(
            $tableau['nom'],
            $tableau['prenom'],
            $tableau['telephone'],
            $tableau['profil'],
            $tableau['adresse'],
            $tableau['numeroPieceIdentite'],
            $tableau['photoRecto'],
            $tableau['photoVerso'],
            $tableau['comptes']
          );
         }

        public function toArray(Object $object): array{
           return [
                'id' => $this->id,
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'adresse' => $this->adresse,
                'telephone' => $this->telephone,
                'numeroPieceIdentite' => $this->numeroPieceIdentite,
                'photoRecto' => $this->photoRecto,
                'photoVerso' => $this->photoVerso,
                'profil' => $this->profil,
                'comptes' => $this->comptes,
            ];
        }



}
