db.createUser({
  user: "admin",
  pwd: "admin123",
  roles: [
    {
      role: "readWrite",
      db: "hyperf_db"
    }
  ]
});

db = db.getSiblingDB('hyperf_db');

db.createCollection('users');

db.users.insertMany([
  {
    name: "João Silva",
    email: "joao@example.com",
    age: 30,
    created_at: new Date(),
    updated_at: new Date()
  },
  {
    name: "Maria Santos",
    email: "maria@example.com",
    age: 25,
    created_at: new Date(),
    updated_at: new Date()
  }
]);
