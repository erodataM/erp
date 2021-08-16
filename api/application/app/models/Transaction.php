<?php
use Phalcon\Mvc\Model;
use Phalcon\Messages\Message as Message;

class Transaction extends Model
{
    public function initialize() {
        $this->setSource('transaction');
        $this->keepSnapshots(true);
    }

    public function validation()
    {
        if (empty($this->provider_id) && empty($this->client_id)) {
            $message = new Message('At least a provider or a client must be provided.');
            $this->appendMessage($message);
            return false;
        }
        if (!empty($this->provider_id) && !empty($this->client_id)) {
            $message = new Message('Provider and client cannot be both provided.');
            $this->appendMessage($message);
            return false;
        }

        $company = Company::find($this->company_id)->getFirst();
        $product = Product::find($this->product_id)->getFirst();

        $quantityForBalance = (int) $this->quantity;
        $quantityForStock = (int) $this->quantity;
        if ($this->id) {
            $oldTransaction = Transaction::find($this->id)->getFirst();
            if ($oldTransaction->company_id == $this->company_id && $this->quantity > $oldTransaction->quantity) {
                $quantityForBalance = $this->quantity - $oldTransaction->quantity;
            }
            if ($oldTransaction->product_id == $this->product_id && $this->quantity > $oldTransaction->quantity) {
                $quantityForStock = $this->quantity - $oldTransaction->quantity;
            }
        }

        if (!empty($this->provider_id)) {
            $totalPrice = $quantityForBalance * (int) $product->price;
            if ($totalPrice > (int) $company->balance) {
                $message = new Message('Company has not enough money to buy this.');
                $this->appendMessage($message);
                return false;
            }
        }
        if (!empty($this->client_id)) {
            if ($quantityForStock > (int) $product->stock) {
                $message = new Message('Product stock insufficient.');
                $this->appendMessage($message);
                return false;
            }
        }

        return true;
    }

    public function afterCreate() {
        $company = Company::find($this->company_id)->getFirst();
        $product = Product::find($this->product_id)->getFirst();
        if (!empty($this->provider_id)) {
            $company->balance = (int) $company->balance - ((int) $this->quantity * (int) $product->price);
            $product->stock = (int) $product->stock + (int) $this->quantity;
        }
        if (!empty($this->client_id)) {
            $company->balance = (int) $company->balance + ((int) $this->quantity * (int) $product->price);
            $product->stock = $product->stock - (int) $this->quantity;
        }
        $company->save();
        $product->save();
    }

    public function afterUpdate() {
        $oldData = $this->getOldSnapshotData();

        if ($oldData['company_id'] != $this->company_id) {
            $oldCompany = Company::find($oldData['company_id'])->getFirst();
            $oldProduct = Product::find($oldData['product_id'])->getFirst();
            $oldCompany->balance = (int) $oldCompany->balance + ((int) $oldData['quantity'] * (int) $oldProduct->price);

            $newCompany = Company::find($this->company_id)->getFirst();
            $newProduct = Product::find($this->product_id)->getFirst();

            $newCompany->balance = (int) $newCompany->balance - ((int) $this->quantity * (int) $newProduct->price);
            $oldCompany->save();
            $newCompany->save();
        }

        if ($oldData['product_id'] != $this->product_id) {
            $oldProduct = Product::find($oldData['product_id'])->getFirst();
            $oldProduct->stock = (int) $oldProduct->stock + (int) $oldData['quantity'];
            $newProduct = Product::find($this->product_id)->getFirst();
            $newProduct->stock = (int) $newProduct->stock - (int) $this->quantity;
            if ($oldData['company_id'] == $this->company_id) {
                $newCompany = Company::find($this->company_id)->getFirst();
                $newCompany->balance = (int) $newCompany->balance - ((int) $this->quantity * (int) $newProduct->price);
                $newCompany->save();
            }
            $oldProduct->save();
            $newProduct->save();
        }

        if ($oldData['quantity'] != $this->quantity) {
            if ($oldData['company_id'] == $this->company_id && $oldData['company_id'] == $this->company_id) {
                $newCompany = Company::find($this->company_id)->getFirst();
                $newProduct = Product::find($this->product_id)->getFirst();
                $newCompany->balance = (int) $newCompany->balance - ((int) $oldData['quantity'] * (int) $newProduct->price);
                $newCompany->balance = (int) $newCompany->balance + ((int) $this->quantity * (int) $newProduct->price);

                $newProduct->stock = (int) $newProduct->stock + (int) $oldData['quantity'];
                $newProduct->stock =  $newProduct->stock - (int) $this->quantity;
                $newCompany->save();
                $newProduct->save();
            }
        }
    }

    public function afterDelete() {
        $company = Company::find($this->company_id)->getFirst();
        $product = Product::find($this->product_id)->getFirst();
        if (!empty($this->provider_id)) {
            $company->balance = (int) $company->balance + ((int) $this->quantity * (int) $product->price);
            $product->stock = (int) $product->stock - (int) $this->quantity;
        }
        if (!empty($this->client_id)) {
            $company->balance = (int) $company->balance - ((int) $this->quantity * (int) $product->price);
            $product->stock = $product->stock + (int) $this->quantity;
        }
        $company->save();
        $product->save();
    }
}