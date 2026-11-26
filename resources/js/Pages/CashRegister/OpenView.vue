<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Form, useForm } from '@inertiajs/vue3';


const form = useForm({
  opening_balance: 0.0,
});

</script>

<template>
  <div class="flex justify-center items-center h-screen">
    <Card class="max-w-sm flex-1">
      <CardHeader>
        <CardTitle></CardTitle>
        <CardDescription></CardDescription>
      </CardHeader>
      <CardContent>
        <Form>
          <div>
            <Label></Label>
            <Input name="opening_balance" />
          </div>
          <div>
            <Button>Registrar</Button>
          </div>
        </Form>
      </CardContent>
    </Card>
  </div>
</template>
